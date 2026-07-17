<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Product;
use App\Models\Production;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->wantsJson()) {
            return response()->json(Production::with(['counter', 'product'])->get());
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
        ]);

        $production = Production::create($validated);

        return response()->json($production->load(['counter', 'product']), 201);
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
        return response()->json($production->load(['counter', 'product']));
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
        ]);

        $production->update($validated);

        return response()->json($production->load(['counter', 'product']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Production $production): JsonResponse
    {
        $production->delete();

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
