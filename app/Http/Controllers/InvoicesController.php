<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(Invoices::with(['sale.customer', 'sale.counter', 'sale.expedition', 'sale.items.product.unit'])->get());
    }

    /**
     * Display the invoice view page.
     */
    public function invoiceView(): View
    {
        return view('administrator.invoice');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sales_id' => ['required', 'exists:sales,id', 'unique:invoices,sales_id'],
            'invoice_number' => ['required', 'string', 'max:255', 'unique:invoices,invoice_number'],
            'type' => ['nullable', 'in:umum'],
            'printed_count' => ['nullable', 'integer', 'min:0'],
        ]);

        $invoice = Invoices::create($validated);

        return response()->json($invoice->load(['sale.customer', 'sale.counter', 'sale.expedition', 'sale.items.product.unit']), 201);
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
    public function show(Invoices $invoice): JsonResponse
    {
        return response()->json($invoice->load(['sale.customer', 'sale.counter', 'sale.expedition', 'sale.items.product.unit']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoices $invoice): JsonResponse
    {
        $validated = $request->validate([
            'sales_id' => ['required', 'exists:sales,id', 'unique:invoices,sales_id,'.$invoice->id],
            'invoice_number' => ['required', 'string', 'max:255', 'unique:invoices,invoice_number,'.$invoice->id],
            'type' => ['nullable', 'in:umum'],
            'printed_count' => ['nullable', 'integer', 'min:0'],
        ]);

        $invoice->update($validated);

        return response()->json($invoice->load(['sale.customer', 'sale.counter', 'sale.expedition', 'sale.items.product.unit']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoices $invoice): JsonResponse
    {
        $invoice->delete();

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(Invoices $invoice): JsonResponse
    {
        return $this->destroy($invoice);
    }
}
