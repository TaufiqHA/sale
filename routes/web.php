<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpeditionController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProductionItemController;
use App\Http\Controllers\ProductWholesaleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleItemController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth')->name('me');

Route::get('/administrator/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('administrator.dashboard');
Route::get('/administrator/dashboard/stats', [DashboardController::class, 'stats'])->middleware('auth')->name('administrator.dashboard.stats');

Route::get('/administrator/stock-monitor', [ProductController::class, 'stockMonitor'])->middleware('auth')->name('administrator.stock-monitor');

Route::apiResource('categories', CategoryController::class)->middleware('auth');
Route::apiResource('units', UnitController::class)->middleware('auth');
Route::apiResource('counters', CounterController::class)->middleware('auth');
Route::apiResource('products', ProductController::class)->middleware('auth');
Route::apiResource('customers', CustomerController::class)->middleware('auth');
Route::apiResource('marketplaces', MarketplaceController::class)->except(['index'])->middleware('auth');
Route::apiResource('couriers', CourierController::class)->except(['index'])->middleware('auth');
Route::apiResource('expeditions', ExpeditionController::class)->except(['index'])->middleware('auth');
Route::apiResource('sales', SaleController::class)->middleware('auth');
Route::apiResource('sale-items', SaleItemController::class)->except(['index'])->middleware('auth');
Route::apiResource('productions', ProductionController::class)->middleware('auth');
Route::apiResource('production-items', ProductionItemController::class)->except(['index'])->middleware('auth');
Route::apiResource('product-wholesales', ProductWholesaleController::class)->except(['index'])->middleware('auth');
