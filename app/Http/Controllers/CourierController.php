<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:couriers,name'],
            'type' => ['sometimes', 'string', 'in:umum,marketplace,keduanya'],
        ]);

        $courier = Courier::create($validated);

        return response()->json($courier, 201);
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
    public function show(Courier $courier): JsonResponse
    {
        return response()->json($courier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Courier $courier): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:couriers,name,'.$courier->id],
            'type' => ['sometimes', 'string', 'in:umum,marketplace,keduanya'],
        ]);

        $courier->update($validated);

        return response()->json($courier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Courier $courier): JsonResponse
    {
        $courier->delete();

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(Courier $courier): JsonResponse
    {
        return $this->destroy($courier);
    }
}
