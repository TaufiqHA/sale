<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Counter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view.
     */
    public function index(): View
    {
        if (auth()->user()->role !== 'administrator') {
            abort(403);
        }

        $counters = Counter::all();
        $categories = Category::all();

        return view('administrator.dashboard', compact('counters', 'categories'));
    }

    /**
     * Get statistics for dashboard widgets and chart.
     */
    public function stats(Request $request): JsonResponse
    {
        if (auth()->user()->role !== 'administrator') {
            abort(403);
        }

        $counterId = $request->input('counter_id');
        $categoryId = $request->input('category_id');

        // 1. Fetch Summary Stats (Omset, Keuntungan, Qty)
        $summary = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->when($counterId, function ($q) use ($counterId) {
                return $q->where('sales.counter_id', $counterId);
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                return $q->where('products.category_id', $categoryId);
            })
            ->select(
                DB::raw('COALESCE(SUM(sale_items.qty), 0) as total_qty'),
                DB::raw('COALESCE(SUM(sale_items.subtotal * (1 - CASE WHEN sales.subtotal > 0 THEN sales.discount * 1.0 / sales.subtotal ELSE 0 END)), 0) as total_omset'),
                DB::raw('COALESCE(SUM((sale_items.subtotal * (1 - CASE WHEN sales.subtotal > 0 THEN sales.discount * 1.0 / sales.subtotal ELSE 0 END)) - (sale_items.qty * products.buy_price)), 0) as total_keuntungan')
            )
            ->first();

        // 2. Fetch Chart Data (Last 7 Days)
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $dates[] = now()->subDays($i)->format('Y-m-d');
        }

        $dailySales = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->when($counterId, function ($q) use ($counterId) {
                return $q->where('sales.counter_id', $counterId);
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                return $q->where('products.category_id', $categoryId);
            })
            ->whereDate('sales.date', '>=', now()->subDays(6)->startOfDay())
            ->whereDate('sales.date', '<=', now()->endOfDay())
            ->select(
                DB::raw('DATE(sales.date) as date_only'),
                DB::raw('COALESCE(SUM(sale_items.subtotal * (1 - CASE WHEN sales.subtotal > 0 THEN sales.discount * 1.0 / sales.subtotal ELSE 0 END)), 0) as omset')
            )
            ->groupBy('date_only')
            ->get()
            ->pluck('omset', 'date_only')
            ->toArray();

        $chartData = [];
        foreach ($dates as $date) {
            $formattedDate = date('j M', strtotime($date));
            $chartData[] = [
                'date' => $formattedDate,
                'omset' => round($dailySales[$date] ?? 0, 2),
            ];
        }

        // 3. Best Sellers (Top 5 Products)
        $bestSellers = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->when($counterId, function ($q) use ($counterId) {
                return $q->where('sales.counter_id', $counterId);
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                return $q->where('products.category_id', $categoryId);
            })
            ->select(
                'products.name as product_name',
                DB::raw('SUM(sale_items.qty) as total_qty'),
                DB::raw('SUM(sale_items.subtotal * (1 - CASE WHEN sales.subtotal > 0 THEN sales.discount * 1.0 / sales.subtotal ELSE 0 END)) as total_omset')
            )
            ->groupBy('sale_items.product_id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 4. Latest Transactions (Last 5 Sales)
        $latestTransactions = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->when($counterId, function ($q) use ($counterId) {
                return $q->where('sales.counter_id', $counterId);
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                return $q->where('products.category_id', $categoryId);
            })
            ->select(
                'sales.id as sale_id',
                'sales.date as date',
                'sales.barcode as barcode',
                DB::raw('SUM(sale_items.subtotal * (1 - CASE WHEN sales.subtotal > 0 THEN sales.discount * 1.0 / sales.subtotal ELSE 0 END)) as filtered_total')
            )
            ->groupBy('sales.id', 'sales.date', 'sales.barcode')
            ->orderByDesc('sales.date')
            ->limit(5)
            ->get();

        return response()->json([
            'summary' => [
                'total_omset' => round($summary->total_omset, 2),
                'total_keuntungan' => round($summary->total_keuntungan, 2),
                'total_qty' => (int) $summary->total_qty,
            ],
            'chart' => $chartData,
            'best_sellers' => $bestSellers,
            'latest_transactions' => $latestTransactions,
        ]);
    }
}
