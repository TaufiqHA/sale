<?php

namespace App\Http\Controllers;

use App\Models\ProductionItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductionItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'production_id' => ['required', 'exists:productions,id'],
            'description' => ['required', 'string'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'qty' => ['required', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
        ]);

        $productionItem = ProductionItem::create($validated);

        return response()->json($productionItem->load('production'), 201);
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
    public function show(ProductionItem $productionItem): JsonResponse
    {
        return response()->json($productionItem->load('production'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductionItem $productionItem): JsonResponse
    {
        $validated = $request->validate([
            'production_id' => ['required', 'exists:productions,id'],
            'description' => ['required', 'string'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'qty' => ['required', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
        ]);

        $productionItem->update($validated);

        return response()->json($productionItem->load('production'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionItem $productionItem): JsonResponse
    {
        $productionItem->delete();

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(ProductionItem $productionItem): JsonResponse
    {
        return $this->destroy($productionItem);
    }
}
