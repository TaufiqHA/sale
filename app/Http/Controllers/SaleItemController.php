<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sale_id' => ['required', 'exists:sales,id'],
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ]);

        $saleItem = DB::transaction(function () use ($validated) {
            $saleItem = SaleItem::create($validated);
            $product = Product::find($validated['product_id']);
            if ($product) {
                $product->decrement('stock', $validated['qty']);
            }

            return $saleItem;
        });

        return response()->json($saleItem->load(['sale', 'product']), 201);
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
    public function show(SaleItem $saleItem): JsonResponse
    {
        return response()->json($saleItem->load(['sale', 'product']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaleItem $saleItem): JsonResponse
    {
        $validated = $request->validate([
            'sale_id' => ['required', 'exists:sales,id'],
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'subtotal' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($saleItem, $validated) {
            // Restore old stock
            $oldProduct = Product::find($saleItem->product_id);
            if ($oldProduct) {
                $oldProduct->increment('stock', $saleItem->qty);
            }

            $saleItem->update($validated);

            // Decrement new stock
            $newProduct = Product::find($validated['product_id']);
            if ($newProduct) {
                $newProduct->decrement('stock', $validated['qty']);
            }
        });

        return response()->json($saleItem->load(['sale', 'product']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleItem $saleItem): JsonResponse
    {
        DB::transaction(function () use ($saleItem) {
            $product = Product::find($saleItem->product_id);
            if ($product) {
                $product->increment('stock', $saleItem->qty);
            }
            $saleItem->delete();
        });

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(SaleItem $saleItem): JsonResponse
    {
        return $this->destroy($saleItem);
    }
}
