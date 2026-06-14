<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

//Dentro de api.php, se definen las rutas que el API va a exponer para su uso.

/*
Nos ayuda a validar la autenticación antes de realizar alguna acción */
Route::middleware('auth:sanctum')
    ->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::apiResource('products', ProductController::class);
        Route::apiResource('orders', OrderController::class);
        
    });

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working'
    ]);
});