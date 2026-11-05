<?php

use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\InventoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PrescriptionController;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->name('v1.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/prescriptions/{prescription}/file', [PrescriptionController::class, 'file'])
        ->middleware('signed')
        ->name('prescriptions.file');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('prescriptions', PrescriptionController::class);
        Route::apiResource('inventories', InventoryController::class);
        Route::apiResource('orders', OrderController::class);
    });
});