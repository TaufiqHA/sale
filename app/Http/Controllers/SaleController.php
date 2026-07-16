<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\Expedition;
use App\Models\Marketplace;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->wantsJson()) {
            return response()->json(Sale::with(['counter', 'customer', 'expedition', 'marketplace', 'courier'])->get());
        }

        return view('administrator.sale', [
            'counters' => Counter::all(),
            'customers' => Customer::all(),
            'expeditions' => Expedition::all(),
            'marketplaces' => Marketplace::all(),
            'couriers' => Courier::all(),
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
            'grand_total' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:tunai,transfer,compliment'],
        ]);

        $sale = Sale::create($validated);

        return response()->json($sale->load(['counter', 'customer', 'expedition', 'marketplace', 'courier']), 201);
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
    public function show(Sale $sale): JsonResponse
    {
        return response()->json($sale->load(['counter', 'customer', 'expedition', 'marketplace', 'courier']));
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
            'grand_total' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:tunai,transfer,compliment'],
        ]);

        $sale->update($validated);

        return response()->json($sale->load(['counter', 'customer', 'expedition', 'marketplace', 'courier']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale): JsonResponse
    {
        $sale->delete();

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
