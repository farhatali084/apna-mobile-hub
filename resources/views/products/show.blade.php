@extends('layouts.app')

@section('title', $product->name . ' - LuxeShop')

@section('content')
<div class="container">
    <!-- Back Button -->
    <div class="navigation-toolbar">
        <a href="{{ route('products.index') }}" class="back-link">
            <i data-lucide="arrow-left"></i> Back to Products
        </a>
    </div>

    <!-- Product Details Card -->
    <div class="details-card">
        <div class="details-image-section">
            <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" class="details-main-img">
        </div>
        
        <div class="details-info-section">
            <span class="details-category">{{ $product->category->name ?? 'General' }}</span>
            <h1 class="details-title">{{ $product->name }}</h1>
            
            <!-- Product Rating -->
            <div class="product-rating" style="margin-bottom: 16px;">
                <span class="stars" style="font-size: 14px;">★ ★ ★ ★ ★</span>
                <span class="rating-count">4.9 (48 verified reviews)</span>
            </div>
            
            <div class="details-price-row">
                <span class="details-price">{{ env('CURRENCY_SYMBOL', '₹') }} {{ number_format($product->price, 2) }}</span>
                @if($product->stock > 0)
                    <span class="badge badge-success">In Stock ({{ $product->stock }})</span>
                @else
                    <span class="badge badge-danger">Out of Stock</span>
                @endif
            </div>
            
            <hr class="separator">
            
            <div class="details-description">
                <h3>Description</h3>
                <p>{{ $product->description }}</p>
            </div>
            
            <hr class="separator">

            <div class="details-actions">
                @if($product->stock > 0)
                    <!-- Add to Cart -->
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <button type="submit" class="btn-primary btn-add-cart">
                            <i data-lucide="shopping-bag"></i> Add to Cart
                        </button>
                    </form>
                @endif

                <!-- WhatsApp Direct Inquiry -->
                <a href="{{ route('cart.inquireSingle', $product->id) }}" class="btn-whatsapp" target="_blank">
                    <i data-lucide="message-circle"></i> Inquire on WhatsApp
                </a>
            </div>
            
            <div class="secure-checkout-badge">
                <i data-lucide="shield-check" class="secure-icon"></i>
                <span>Direct WhatsApp communication with store admin. Safe & secure ordering.</span>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <section class="related-products-section">
            <h2 class="section-title">You May Also Like</h2>
            <div class="products-grid">
                @foreach($relatedProducts as $related)
                    <div class="product-card">
                        <div class="product-image-wrapper">
                            <a href="{{ route('products.show', $related->slug) }}">
                                <img src="{{ $related->getImageUrl() }}" alt="{{ $related->name }}" class="product-image">
                            </a>
                        </div>
                        <div class="product-card-body">
                            <span class="product-card-category">{{ $related->category->name ?? 'General' }}</span>
                            <h3 class="product-card-title">
                                <a href="{{ route('products.show', $related->slug) }}">{{ $related->name }}</a>
                            </h3>
                            <div class="product-card-footer">
                                <span class="product-card-price">{{ env('CURRENCY_SYMBOL', '₹') }} {{ number_format($related->price, 2) }}</span>
                                <a href="{{ route('products.show', $related->slug) }}" class="btn-view-details">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
