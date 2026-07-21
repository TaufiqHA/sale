<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\Expedition;
use App\Models\Invoices;
use App\Models\Marketplace;
use App\Models\Product;
use App\Models\Recipts;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->wantsJson()) {
            return response()->json(Sale::with(['counter', 'customer', 'expedition', 'marketplace', 'courier', 'items.product'])->get());
        }

        return view('administrator.sale', [
            'counters' => Counter::all(),
            'customers' => Customer::all(),
            'expeditions' => Expedition::all(),
            'marketplaces' => Marketplace::all(),
            'couriers' => Courier::all(),
            'products' => Product::where('status', true)->with(['wholeprices', 'unit'])->get(),
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
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'grand_total' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:tunai,transfer,compliment'],
            'items' => ['sometimes', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'min:0.01'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.subtotal' => ['required', 'numeric', 'min:0'],
            'items.*.is_wholeprice' => ['nullable', 'boolean'],
            'items.*.wholeprice_id' => ['nullable', 'exists:product_wholeprices,id'],
        ]);

        if ($errorResponse = $this->validateItemQuantities($validated['items'] ?? [])) {
            return $errorResponse;
        }

        $sale = DB::transaction(function () use ($validated) {
            $sale = Sale::create(Arr::except($validated, ['items']));
            if (! empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    $sale->items()->create($item);
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock', $item['qty']);
                    }
                }
            }

            if ($sale->type === 'umum') {
                Invoices::create([
                    'sales_id' => $sale->id,
                    'invoice_number' => 'INV-'.str_pad((string) $sale->id, 5, '0', STR_PAD_LEFT),
                    'type' => 'umum',
                    'printed_count' => 0,
                ]);
            }

            Recipts::create([
                'sales_id' => $sale->id,
                'receipt_number' => 'RCP-'.str_pad((string) $sale->id, 5, '0', STR_PAD_LEFT),
                'type' => $sale->type,
                'printed_count' => 0,
            ]);

            return $sale;
        });

        return response()->json($sale->load(['counter', 'customer', 'expedition', 'marketplace', 'courier', 'items.product']), 201);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('administrator.createSale', [
            'counters' => Counter::all(),
            'customers' => Customer::all(),
            'expeditions' => Expedition::all(),
            'marketplaces' => Marketplace::all(),
            'couriers' => Courier::all(),
            'products' => Product::where('status', true)->with(['wholeprices', 'unit'])->get(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale): JsonResponse
    {
        return response()->json($sale->load(['counter', 'customer', 'expedition', 'marketplace', 'courier', 'items.product']));
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
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'grand_total' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:tunai,transfer,compliment'],
            'items' => ['sometimes', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'min:0.01'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.subtotal' => ['required', 'numeric', 'min:0'],
            'items.*.is_wholeprice' => ['nullable', 'boolean'],
            'items.*.wholeprice_id' => ['nullable', 'exists:product_wholeprices,id'],
        ]);

        if ($errorResponse = $this->validateItemQuantities($validated['items'] ?? [])) {
            return $errorResponse;
        }

        DB::transaction(function () use ($sale, $validated) {
            $sale->update(Arr::except($validated, ['items']));
            if (isset($validated['items'])) {
                foreach ($sale->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->qty);
                    }
                }

                $sale->items()->delete();

                foreach ($validated['items'] as $item) {
                    $sale->items()->create($item);
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock', $item['qty']);
                    }
                }
            }
        });

        return response()->json($sale->load(['counter', 'customer', 'expedition', 'marketplace', 'courier', 'items.product']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale): JsonResponse
    {
        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->qty);
                }
            }
            $sale->delete();
        });

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(Sale $sale): JsonResponse
    {
        return $this->destroy($sale);
    }

    /**
     * Validate that non-Kg item quantities are integers >= 1, and Kg items are > 0.
     */
    private function validateItemQuantities(array $items): ?JsonResponse
    {
        foreach ($items as $index => $item) {
            $product = Product::with('unit')->find($item['product_id'] ?? null);
            if (! $product) {
                continue;
            }

            $unitName = strtolower($product->unit->name ?? '');
            $qty = (float) ($item['qty'] ?? 0);

            if ($unitName !== 'kg') {
                if ($qty < 1 || fmod($qty, 1.0) != 0.0) {
                    return response()->json([
                        'message' => "Quantity untuk produk '{$product->name}' harus berupa angka bulat minimal 1 (bukan desimal).",
                        'errors' => [
                            "items.{$index}.qty" => ["Quantity untuk produk '{$product->name}' harus berupa angka bulat minimal 1."],
                        ],
                    ], 422);
                }
            } else {
                if ($qty <= 0) {
                    return response()->json([
                        'message' => "Quantity untuk produk '{$product->name}' harus lebih dari 0.",
                        'errors' => [
                            "items.{$index}.qty" => ["Quantity untuk produk '{$product->name}' harus lebih dari 0."],
                        ],
                    ], 422);
                }
            }
        }

        return null;
    }
}
