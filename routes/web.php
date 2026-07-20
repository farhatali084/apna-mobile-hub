<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

// Storefront routes
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/add-to-cart/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/update-cart', [CartController::class, 'update'])->name('cart.update');
Route::delete('/remove-from-cart', [CartController::class, 'remove'])->name('cart.remove');

// WhatsApp redirection routes
Route::get('/inquire-single/{id}', [CartController::class, 'inquireSingle'])->name('cart.inquireSingle');
Route::post('/checkout-whatsapp', [CartController::class, 'inquireCart'])->name('cart.inquireCart');

// About Us & Contact Us routes
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

