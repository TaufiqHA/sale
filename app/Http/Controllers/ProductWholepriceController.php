<?php

namespace App\Http\Controllers;

use App\Models\ProductWholeprice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductWholepriceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'minimum_qty' => ['required', 'integer', 'min:1'],
            'wholeprice_price' => ['required', 'numeric', 'min:0'],
        ]);

        $productWholeprice = ProductWholeprice::create($validated);

        return response()->json($productWholeprice->load('product'), 201);
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
    public function show(ProductWholeprice $productWholeprice): JsonResponse
    {
        return response()->json($productWholeprice->load('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductWholeprice $productWholeprice): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'minimum_qty' => ['required', 'integer', 'min:1'],
            'wholeprice_price' => ['required', 'numeric', 'min:0'],
        ]);

        $productWholeprice->update($validated);

        return response()->json($productWholeprice->load('product'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductWholeprice $productWholeprice): JsonResponse
    {
        $productWholeprice->delete();

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(ProductWholeprice $productWholeprice): JsonResponse
    {
        return $this->destroy($productWholeprice);
    }
}
