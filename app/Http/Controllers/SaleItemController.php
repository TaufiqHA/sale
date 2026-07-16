<?php

namespace App\Http\Controllers;

use App\Models\SaleItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        $saleItem = SaleItem::create($validated);

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

        $saleItem->update($validated);

        return response()->json($saleItem->load(['sale', 'product']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleItem $saleItem): JsonResponse
    {
        $saleItem->delete();

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
