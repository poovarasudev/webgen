<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [\App\Http\Controllers\Api\V1\AuthController::class, 'authenticate'])->name('login');
    Route::post('register', [\App\Http\Controllers\Api\V1\AuthController::class, 'register'])->name('register');

    Route::group(['middleware' => 'jwt'], function () {
        Route::get('profile', [\App\Http\Controllers\Api\V1\AuthController::class, 'profile'])->name('profile');
        Route::delete('logout', [\App\Http\Controllers\Api\V1\AuthController::class, 'logout'])->name('logout');

        // Resource route for products.
        Route::apiResource('products', \App\Http\Controllers\Api\V1\ProductController::class, ['except' => ['show', 'edit', 'create']]);
    });
});
