<?php

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

use Illuminate\Support\Facades\Route;
use Wave\Facades\Wave;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PublicProductController;

// Wave routes
Wave::routes();

Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('reservations', ReservationController::class);
});

Route::get('/produit/{slug}', [PublicProductController::class, 'show'])->name('public.product.show');
