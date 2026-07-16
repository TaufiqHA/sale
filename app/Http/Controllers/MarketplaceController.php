<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:marketplaces,name'],
        ]);

        $marketplace = Marketplace::create($validated);

        return response()->json($marketplace, 201);
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
    public function show(Marketplace $marketplace): JsonResponse
    {
        return response()->json($marketplace);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marketplace $marketplace): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:marketplaces,name,'.$marketplace->id],
        ]);

        $marketplace->update($validated);

        return response()->json($marketplace);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marketplace $marketplace): JsonResponse
    {
        $marketplace->delete();

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(Marketplace $marketplace): JsonResponse
    {
        return $this->destroy($marketplace);
    }
}
