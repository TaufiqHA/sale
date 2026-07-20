<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\Expedition;
use App\Models\Marketplace;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->wantsJson()) {
            return response()->json(Sale::with(['counter', 'customer', 'expedition', 'marketplace', 'courier', 'items.product'])->get());
        }

        return view('administrator.sale', [
            'counters' => Counter::all(),
            'customers' => Customer::all(),
            'expeditions' => Expedition::all(),
            'marketplaces' => Marketplace::all(),
            'couriers' => Courier::all(),
            'products' => Product::where('status', true)->with('wholeprices')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'counter_id' => ['required', 'exists:counters,id'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'expedition_id' => ['nullable', 'exists:expeditions,id'],
            'barcode' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:umum,marketplace'],
            'marketplace_id' => ['nullable', 'exists:marketplaces,id'],
            'courier_id' => ['nullable', 'exists:couriers,id'],
            'date' => ['required', 'date'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'grand_total' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:tunai,transfer,compliment'],
            'items' => ['sometimes', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.subtotal' => ['required', 'numeric', 'min:0'],
            'items.*.is_wholeprice' => ['nullable', 'boolean'],
            'items.*.wholeprice_id' => ['nullable', 'exists:product_wholeprices,id'],
        ]);

        $sale = DB::transaction(function () use ($validated) {
            $sale = Sale::create(Arr::except($validated, ['items']));
            if (! empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    $sale->items()->create($item);
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock', $item['qty']);
                    }
                }
            }

            return $sale;
        });

        return response()->json($sale->load(['counter', 'customer', 'expedition', 'marketplace', 'courier', 'items.product']), 201);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('administrator.createSale', [
            'counters' => Counter::all(),
            'customers' => Customer::all(),
            'expeditions' => Expedition::all(),
            'marketplaces' => Marketplace::all(),
            'couriers' => Courier::all(),
            'products' => Product::where('status', true)->with('wholeprices')->get(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale): JsonResponse
    {
        return response()->json($sale->load(['counter', 'customer', 'expedition', 'marketplace', 'courier', 'items.product']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale): JsonResponse
    {
        $validated = $request->validate([
            'counter_id' => ['required', 'exists:counters,id'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'expedition_id' => ['nullable', 'exists:expeditions,id'],
            'barcode' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:umum,marketplace'],
            'marketplace_id' => ['nullable', 'exists:marketplaces,id'],
            'courier_id' => ['nullable', 'exists:couriers,id'],
            'date' => ['required', 'date'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'grand_total' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:tunai,transfer,compliment'],
            'items' => ['sometimes', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.subtotal' => ['required', 'numeric', 'min:0'],
            'items.*.is_wholeprice' => ['nullable', 'boolean'],
            'items.*.wholeprice_id' => ['nullable', 'exists:product_wholeprices,id'],
        ]);

        DB::transaction(function () use ($sale, $validated) {
            $sale->update(Arr::except($validated, ['items']));
            if (isset($validated['items'])) {
                foreach ($sale->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->qty);
                    }
                }

                $sale->items()->delete();

                foreach ($validated['items'] as $item) {
                    $sale->items()->create($item);
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock', $item['qty']);
                    }
                }
            }
        });

        return response()->json($sale->load(['counter', 'customer', 'expedition', 'marketplace', 'courier', 'items.product']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale): JsonResponse
    {
        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->qty);
                }
            }
            $sale->delete();
        });

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(Sale $sale): JsonResponse
    {
        return $this->destroy($sale);
    }
}
