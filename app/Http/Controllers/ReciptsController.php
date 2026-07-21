<?php

namespace App\Http\Controllers;

use App\Models\Recipts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReciptsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(Recipts::with(['sale.customer', 'sale.counter', 'sale.marketplace', 'sale.courier', 'sale.expedition', 'sale.items.product.unit'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sales_id' => ['required', 'exists:sales,id', 'unique:recipts,sales_id'],
            'receipt_number' => ['required', 'string', 'max:255', 'unique:recipts,receipt_number'],
            'type' => ['nullable', 'in:umum,marketplace'],
            'printed_count' => ['nullable', 'integer', 'min:0'],
        ]);

        $recipt = Recipts::create($validated);

        return response()->json($recipt->load(['sale.customer', 'sale.counter', 'sale.marketplace', 'sale.courier', 'sale.expedition', 'sale.items.product.unit']), 201);
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
    public function show(Recipts $recipt): JsonResponse
    {
        return response()->json($recipt->load(['sale.customer', 'sale.counter', 'sale.marketplace', 'sale.courier', 'sale.expedition', 'sale.items.product.unit']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipts $recipt): JsonResponse
    {
        $validated = $request->validate([
            'sales_id' => ['required', 'exists:sales,id', 'unique:recipts,sales_id,'.$recipt->id],
            'receipt_number' => ['required', 'string', 'max:255', 'unique:recipts,receipt_number,'.$recipt->id],
            'type' => ['nullable', 'in:umum,marketplace'],
            'printed_count' => ['nullable', 'integer', 'min:0'],
        ]);

        $recipt->update($validated);

        return response()->json($recipt->load(['sale.customer', 'sale.counter', 'sale.marketplace', 'sale.courier', 'sale.expedition', 'sale.items.product.unit']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipts $recipt): JsonResponse
    {
        $recipt->delete();

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(Recipts $recipt): JsonResponse
    {
        return $this->destroy($recipt);
    }
}
