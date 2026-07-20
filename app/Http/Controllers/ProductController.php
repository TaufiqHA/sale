<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->wantsJson()) {
            return response()->json(Product::with(['category', 'unit', 'counter', 'wholeprices'])->get());
        }

        return view('administrator.product');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'counter_id' => ['required', 'exists:counters,id'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku'],
            'barcode' => ['nullable', 'string', 'max:255', 'unique:products,barcode'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'buy_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'is_wholeprice' => ['nullable', 'boolean'],
            'wholeprices' => ['nullable', 'array'],
            'wholeprices.*.minimum_qty' => ['required_if:is_wholeprice,true', 'integer', 'min:1'],
            'wholeprices.*.wholeprice_price' => ['required_if:is_wholeprice,true', 'numeric', 'min:0'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        if ($request->boolean('is_wholeprice') && $request->has('wholeprices')) {
            foreach ($request->input('wholeprices') as $tier) {
                if (! empty($tier['minimum_qty']) && isset($tier['wholeprice_price'])) {
                    $product->wholeprices()->create([
                        'minimum_qty' => $tier['minimum_qty'],
                        'wholeprice_price' => $tier['wholeprice_price'],
                    ]);
                }
            }
        }

        return response()->json($product->load(['category', 'unit', 'counter', 'wholeprices']), 201);
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
    public function show(Product $product): JsonResponse
    {
        return response()->json($product->load(['category', 'unit', 'counter', 'wholeprices']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'counter_id' => ['required', 'exists:counters,id'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku,'.$product->id],
            'barcode' => ['nullable', 'string', 'max:255', 'unique:products,barcode,'.$product->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'buy_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'is_wholeprice' => ['nullable', 'boolean'],
            'wholeprices' => ['nullable', 'array'],
            'wholeprices.*.minimum_qty' => ['required_if:is_wholeprice,true', 'integer', 'min:1'],
            'wholeprices.*.wholeprice_price' => ['required_if:is_wholeprice,true', 'numeric', 'min:0'],
        ]);

        if ($request->boolean('remove_image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = null;
        } elseif ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        if ($request->boolean('is_wholeprice') && $request->has('wholeprices')) {
            $product->wholeprices()->delete();
            foreach ($request->input('wholeprices') as $tier) {
                if (! empty($tier['minimum_qty']) && isset($tier['wholeprice_price'])) {
                    $product->wholeprices()->create([
                        'minimum_qty' => $tier['minimum_qty'],
                        'wholeprice_price' => $tier['wholeprice_price'],
                    ]);
                }
            }
        } else {
            $product->wholeprices()->delete();
        }

        return response()->json($product->load(['category', 'unit', 'counter', 'wholeprices']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return response()->json(null, 204);
    }

    /**
     * Alias for destroy.
     */
    public function delete(Product $product): JsonResponse
    {
        return $this->destroy($product);
    }

    /**
     * Display the stock monitor page.
     */
    public function stockMonitor(Request $request): JsonResponse|View
    {
        if ($request->wantsJson()) {
            return response()->json(
                Product::with(['category', 'unit', 'counter'])
                    ->orderBy('stock', 'asc')
                    ->get()
            );
        }

        return view('administrator.stock-monitor');
    }
}
