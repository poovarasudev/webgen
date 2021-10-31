<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth:web'], function () {
    // Resource route for products.
    Route::resource('products', ProductController::class, ['except' => ['show', 'edit', 'create']]);
    // Route to get products for yajra datatable request.
    Route::get('get-products', [ProductController::class, 'getProducts'])->name('get-products');
});
