<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Product;
use App\Models\Production;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->wantsJson()) {
            return response()->json(Production::with(['counter', 'product', 'productionItems'])->get());
        }

        return view('administrator.production', [
            'counters' => Counter::all(),
            'products' => Product::where('status', true)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'counter_id' => ['required', 'exists:counters,id'],
            'product_id' => ['required', 'exists:products,id'],
            'production_date' => ['required', 'date'],
            'total_cost' => ['required', 'numeric', 'min:0'],
            'total_result' => ['required', 'integer', 'min:0'],
            'hpp' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'estimated_profit' => ['required', 'numeric'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,completed,cancelled'],
            'items' => ['sometimes', 'array'],
            'items.*.description' => ['required', 'string'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.qty' => ['required', 'numeric', 'min:0'],
            'items.*.total' => ['required', 'numeric', 'min:0'],
        ]);

        $production = DB::transaction(function () use ($validated) {
            $production = Production::create(Arr::except($validated, ['items']));
            if (! empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    $production->productionItems()->create($item);
                }
            }

            if ($production->status === 'completed') {
                $product = Product::find($production->product_id);
                if ($product) {
                    $product->increment('stock', $production->total_result);
                    $product->update([
                        'buy_price' => $production->hpp,
                        'sell_price' => $production->selling_price,
                    ]);
                }
            }

            return $production;
        });

        return response()->json($production->load(['counter', 'product', 'productionItems']), 201);
    }

    /**
     * Alias for store.
     */
    public function create(Request $request): JsonResponse
    {
        return $this->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Production $production): JsonResponse
    {
        return response()->json($production->load(['counter', 'product', 'productionItems']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Production $production): JsonResponse
    {
        $validated = $request->validate([
            'counter_id' => ['required', 'exists:counters,id'],
            'product_id' => ['required', 'exists:products,id'],
            'production_date' => ['required', 'date'],
            'total_cost' => ['required', 'numeric', 'min:0'],
            'total_result' => ['required', 'integer', 'min:0'],
            'hpp' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'estimated_profit' => ['required', 'numeric'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,completed,cancelled'],
            'items' => ['sometimes', 'array'],
            'items.*.description' => ['required', 'string'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.qty' => ['required', 'numeric', 'min:0'],
            'items.*.total' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($production, $validated) {
            $oldStatus = $production->status;
            $oldProductId = $production->product_id;
            $oldTotalResult = $production->total_result;

            $production->update(Arr::except($validated, ['items']));
            if (isset($validated['items'])) {
                $production->productionItems()->delete();
                foreach ($validated['items'] as $item) {
                    $production->productionItems()->create($item);
                }
            }

            // Adjust stock
            if ($oldStatus === 'completed') {
                $oldProduct = Product::find($oldProductId);
                if ($oldProduct) {
                    $oldProduct->decrement('stock', $oldTotalResult);
                }
            }

            if ($production->status === 'completed') {
                $newProduct = Product::find($production->product_id);
                if ($newProduct) {
                    $newProduct->increment('stock', $production->total_result);
                    $newProduct->update([
                        'buy_price' => $production->hpp,
                        'sell_price' => $production->selling_price,
                    ]);
                }
            }
        });

        return response()->json($production->load(['counter', 'product', 'productionItems']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Production $production): JsonResponse
    {
        DB::transaction(function () use ($production) {
            if ($production->status === 'completed') {
                $product = Product::find($production->product_id);
                if ($product) {
                    $product->decrement('stock', $production->total_result);
                }
            }
            $production->delete();
        });

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(Production $production): JsonResponse
    {
        return $this->destroy($production);
    }
}
