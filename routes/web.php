<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth')->name('me');

Route::get('/administrator/dashboard', function () {
    if (auth()->user()->role !== 'administrator') {
        abort(403);
    }

    return view('administrator.dashboard');
})->middleware('auth')->name('administrator.dashboard');
