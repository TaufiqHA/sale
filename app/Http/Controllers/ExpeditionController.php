<?php

namespace App\Http\Controllers;

use App\Models\Expedition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpeditionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:expeditions,name'],
        ]);

        $expedition = Expedition::create($validated);

        return response()->json($expedition, 201);
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
    public function show(Expedition $expedition): JsonResponse
    {
        return response()->json($expedition);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expedition $expedition): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:expeditions,name,'.$expedition->id],
        ]);

        $expedition->update($validated);

        return response()->json($expedition);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expedition $expedition): JsonResponse
    {
        $expedition->delete();

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(Expedition $expedition): JsonResponse
    {
        return $this->destroy($expedition);
    }
}
