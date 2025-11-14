<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

Route::post('login', [AuthController::class, 'login']);
Route::group(['as' => 'api.', 'middleware' =>['auth:api']], function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('orders', OrderController::class)->except(['update', 'destroy']);
});