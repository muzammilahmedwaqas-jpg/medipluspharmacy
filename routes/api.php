<?php

use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\InventoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PrescriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->name('v1.')->group(function () {
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('prescriptions', PrescriptionController::class);
    Route::apiResource('inventories', InventoryController::class);
    Route::apiResource('orders', OrderController::class);
});