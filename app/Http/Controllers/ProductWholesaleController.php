<?php

namespace App\Http\Controllers;

use App\Models\ProductWholesale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductWholesaleController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'minimum_qty' => ['required', 'integer', 'min:1'],
            'wholesale_price' => ['required', 'numeric', 'min:0'],
        ]);

        $productWholesale = ProductWholesale::create($validated);

        return response()->json($productWholesale->load('product'), 201);
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
    public function show(ProductWholesale $productWholesale): JsonResponse
    {
        return response()->json($productWholesale->load('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductWholesale $productWholesale): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'minimum_qty' => ['required', 'integer', 'min:1'],
            'wholesale_price' => ['required', 'numeric', 'min:0'],
        ]);

        $productWholesale->update($validated);

        return response()->json($productWholesale->load('product'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductWholesale $productWholesale): JsonResponse
    {
        $productWholesale->delete();

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(ProductWholesale $productWholesale): JsonResponse
    {
        return $this->destroy($productWholesale);
    }
}
