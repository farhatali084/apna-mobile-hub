<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderPdfController;

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
Route::post('/inquire-variants', [CartController::class, 'inquireVariants'])->name('cart.inquireVariants');
Route::post('/add-variants-to-cart', [CartController::class, 'addVariants'])->name('cart.addVariants');

// Order PDF routes
Route::get('/order/{order_number}/pdf', [OrderPdfController::class, 'downloadPdf'])->name('order.pdf');
Route::get('/order/{order_number}/view-pdf', [OrderPdfController::class, 'viewPdf'])->name('order.viewPdf');

// About Us & Contact Us routes
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

// Dynamic XML Sitemap
Route::get('/sitemap.xml', function () {
    $products = \App\Models\Product::select('slug', 'updated_at')->get();
    $categories = \App\Models\Category::select('slug', 'updated_at')->get();

    $content = '<?xml version="1.0" encoding="UTF-8"?>';
    $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    // Static pages
    $staticPages = [
        ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
        ['loc' => url('/about'), 'priority' => '0.7', 'changefreq' => 'monthly'],
        ['loc' => url('/contact'), 'priority' => '0.7', 'changefreq' => 'monthly'],
    ];

    foreach ($staticPages as $page) {
        $content .= '<url>';
        $content .= '<loc>' . $page['loc'] . '</loc>';
        $content .= '<changefreq>' . $page['changefreq'] . '</changefreq>';
        $content .= '<priority>' . $page['priority'] . '</priority>';
        $content .= '</url>';
    }

    // Category pages
    foreach ($categories as $category) {
        $content .= '<url>';
        $content .= '<loc>' . url('/?category=' . $category->slug) . '</loc>';
        $content .= '<lastmod>' . $category->updated_at->toW3cString() . '</lastmod>';
        $content .= '<changefreq>weekly</changefreq>';
        $content .= '<priority>0.8</priority>';
        $content .= '</url>';
    }

    // Product pages
    foreach ($products as $product) {
        $content .= '<url>';
        $content .= '<loc>' . url('/product/' . $product->slug) . '</loc>';
        $content .= '<lastmod>' . $product->updated_at->toW3cString() . '</lastmod>';
        $content .= '<changefreq>weekly</changefreq>';
        $content .= '<priority>0.9</priority>';
        $content .= '</url>';
    }

    $content .= '</urlset>';

    return response($content, 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');
