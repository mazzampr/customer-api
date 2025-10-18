<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GiftController;
use App\Http\Controllers\CustomerController;
use App\Models\Customer;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Routes
Route::prefix('v1')->group(function () {
    Route::resource('customers', CustomerController::class)->only([
        'index', 'show']);
    Route::get('list/customer', [CustomerController::class, 'listCustomer']);
    Route::post('customers/{custId}/confirm', [CustomerController::class, 'confirm']);
    Route::get('gifts/summary', [CustomerController::class, 'summary']);
});

