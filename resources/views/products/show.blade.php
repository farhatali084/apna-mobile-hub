@extends('layouts.app')

@section('title', $product->name . ' - Buy Wholesale | Apna Mobile Hub')

@section('meta_description', $product->description . ' Buy at wholesale price ₹' . number_format($product->price, 2) . ' from Apna Mobile Hub, Jamshedpur. GST billing & pan India delivery.')
@section('meta_keywords', $product->name . ', ' . ($product->category->name ?? '') . ' wholesale, buy ' . $product->name . ' bulk, Apna Mobile Hub')
@section('og_type', 'product')
@section('og_title', $product->name . ' - ₹' . number_format($product->price, 2) . ' | Apna Mobile Hub')
@section('og_description', $product->description)
@section('og_image', $product->getImageUrl())

@section('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "description": "{{ $product->description }}",
    "image": "{{ $product->getImageUrl() }}",
    "sku": "AMH-{{ $product->id }}",
    "brand": {
        "@type": "Brand",
        "name": "Apna Mobile Hub"
    },
    "category": "{{ $product->category->name ?? 'Mobile Accessories' }}",
    "offers": {
        "@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "INR",
        "price": "{{ $product->price }}",
        "availability": "{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "seller": {
            "@type": "Organization",
            "name": "Apna Mobile Hub"
        }
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ $product->rating ?? 4.9 }}",
        "reviewCount": "{{ $product->rating_count ?? 10 }}"
    }
}
</script>
@endsection

@section('content')
<div class="container">
    <!-- Back Button -->
    <div class="navigation-toolbar">
        <a href="{{ route('products.index') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Back to Products
        </a>
    </div>

    <!-- Product Details Card -->
    <div class="details-card">
        @php $allImages = $product->getAllImageUrls(); @endphp
        <div class="details-image-section">
            <div class="detail-carousel" data-current="0">
                <div class="detail-carousel-main">
                    @foreach($allImages as $i => $imgUrl)
                        <img src="{{ $imgUrl }}" alt="{{ $product->name }} - Image {{ $i + 1 }}" class="details-main-img {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}">
                    @endforeach
                </div>
                @if(count($allImages) > 1)
                    <div class="detail-carousel-arrows">
                        <button type="button" class="carousel-arrow carousel-arrow-prev" onclick="detailCarousel(this, -1)">
                            <i class="fa-solid fa-chevron-left" style="width:20px;height:20px;"></i>
                        </button>
                        <span class="carousel-counter"><span class="carousel-current">1</span> / {{ count($allImages) }}</span>
                        <button type="button" class="carousel-arrow carousel-arrow-next" onclick="detailCarousel(this, 1)">
                            <i class="fa-solid fa-chevron-right" style="width:20px;height:20px;"></i>
                        </button>
                    </div>
                    <div class="detail-carousel-thumbs">
                        @foreach($allImages as $i => $imgUrl)
                            <img src="{{ $imgUrl }}" alt="Thumb {{ $i + 1 }}" class="carousel-thumb {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}" onclick="detailCarouselGoto(this, {{ $i }})">
                        @endforeach
                    </div>
                @endif
            </div>
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
                <span class="details-price">{{ env('CURRENCY_SYMBOL', '₹') }} {{ number_format($product->price, 2) }} <small style="font-size:13px;font-weight:500;color:#64748b;">per piece</small></span>
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

            {{-- ====== SIZE / VARIANT QUANTITY SELECTOR ====== --}}
            @if($filterGroups->count() > 0)
                <div class="variant-order-section" id="variant-order-section">
                    @foreach($filterGroups as $group)
                        <div class="variant-group-block">
                            <div class="variant-group-header">
                                <span class="variant-group-label">{{ strtoupper($group->name) }}</span>
                                <span class="variant-group-count" id="count-{{ $group->id }}">
                                    <span id="selected-count-{{ $group->id }}">0</span> selected
                                </span>
                            </div>

                            <div class="variant-rows-list">
                                @foreach($group->values as $val)
                                    @php $minQty = $val->min_qty ?? 1; @endphp
                                    <div class="variant-row" data-group="{{ $group->id }}" data-value-id="{{ $val->id }}" data-value-name="{{ $val->value }}" data-min-qty="{{ $minQty }}">
                                        <div class="variant-row-left">
                                            @if($val->color_hex)
                                                <span class="variant-color-dot" style="background:{{ $val->color_hex }};"></span>
                                            @endif
                                            <div>
                                                <span class="variant-name">{{ $val->value }}</span>
                                                <span class="variant-price-tag">{{ env('CURRENCY_SYMBOL', '₹') }}{{ number_format($product->price, 0) }} per piece</span>
                                                @if($minQty > 1)
                                                    <span class="moq-badge">Min. order: {{ $minQty }} pcs</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="variant-qty-controls">
                                            <button type="button" class="qty-btn qty-minus" onclick="changeVariantQty(this, -1)" data-group="{{ $group->id }}">−</button>
                                            <input type="number" class="variant-qty-input" value="0" min="0"
                                                   data-group="{{ $group->id }}"
                                                   data-value-id="{{ $val->id }}"
                                                   data-value-name="{{ $val->value }}"
                                                   data-price="{{ $product->price }}"
                                                   data-min-qty="{{ $minQty }}"
                                                   oninput="onVariantQtyInput(this)">
                                            <button type="button" class="qty-btn qty-plus" onclick="changeVariantQty(this, 1)" data-group="{{ $group->id }}">+</button>
                                        </div>
                                        <div class="moq-warning" id="moq-warn-{{ $val->id }}" style="display:none;"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Order Summary Bar -->
                    <div class="variant-order-summary" id="variant-order-summary" style="display:none;">
                        <div class="order-summary-info">
                            <span class="summary-label">Your Selection:</span>
                            <span class="summary-total" id="summary-total-text">0 pieces · ₹0</span>
                        </div>
                        <div class="order-summary-actions">
                            <button type="button" class="btn-add-cart-variant" onclick="addVariantsToCart()">
                                <i class="fa-solid fa-bag-shopping"></i> Add to Cart
                            </button>
                            <button type="button" class="btn-whatsapp-variant" onclick="openVariantOrderModal()">
                                <i class="fa-brands fa-whatsapp"></i> Order on WhatsApp
                            </button>
                        </div>
                    </div>
                </div>
            @else
                {{-- Fallback if no filter groups (simple product with no sizes) --}}
                <div class="details-actions">
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <button type="submit" class="btn-primary btn-add-cart">
                                <i class="fa-solid fa-bag-shopping"></i> Add to Cart
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('cart.inquireSingle', $product->id) }}" class="btn-whatsapp" target="_blank">
                        <i class="fa-solid fa-message"></i> Inquire on WhatsApp
                    </a>
                </div>
            @endif

            <div class="secure-checkout-badge">
                <i class="fa-solid fa-shield-halved" class="secure-icon"></i>
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
                        @php $relatedImages = $related->getAllImageUrls(); @endphp
                        <div class="product-image-wrapper" style="position: relative;" x-data="{ activeImg: 0, images: {{ json_encode($relatedImages) }} }">
                            <a href="{{ route('products.show', $related->slug) }}" style="display: block; width: 100%; height: 100%;">
                                <img :src="images[activeImg]" alt="{{ $related->name }}" class="product-image" style="width: 100%; height: 100%; object-fit: cover;">
                            </a>
                            @if(count($relatedImages) > 1)
                                <button @click.prevent="activeImg = activeImg === 0 ? images.length - 1 : activeImg - 1" style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.1); color: var(--text-primary); transition: all 0.2s;" onmouseover="this.style.backgroundColor='var(--accent-orange)'; this.style.color='#fff'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.9)'; this.style.color='var(--text-primary)'">
                                    <i class="fa-solid fa-chevron-left" style="font-size: 11px;"></i>
                                </button>
                                <button @click.prevent="activeImg = activeImg === images.length - 1 ? 0 : activeImg + 1" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.1); color: var(--text-primary); transition: all 0.2s;" onmouseover="this.style.backgroundColor='var(--accent-orange)'; this.style.color='#fff'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.9)'; this.style.color='var(--text-primary)'">
                                    <i class="fa-solid fa-chevron-right" style="font-size: 11px;"></i>
                                </button>
                            @endif
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

<style>
/* ===== VARIANT ORDER SECTION ===== */
.variant-order-section {
    margin: 0 0 20px;
}
.variant-group-block {
    margin-bottom: 24px;
}
.variant-group-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}
.variant-group-label {
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.08em;
    color: var(--text-secondary, #64748b);
    font-family: 'Montserrat', sans-serif;
}
.variant-group-count {
    font-size: 12px;
    color: #0088FF;
    font-weight: 600;
}
.variant-rows-list {
    display: flex;
    flex-direction: column;
    gap: 0;
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 12px;
    overflow: hidden;
}
.variant-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    border-bottom: 1px solid var(--border-color, #e2e8f0);
    transition: background 0.15s;
    gap: 12px;
    flex-wrap: wrap;
}
.variant-row:last-child {
    border-bottom: none;
}
.variant-row.has-qty {
    background: linear-gradient(90deg, rgba(0,136,255,0.04) 0%, transparent 100%);
}
.variant-row-left {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 0;
}
.variant-color-dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid rgba(0,0,0,0.08);
    flex-shrink: 0;
}
.variant-name {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary, #0f172a);
    word-break: break-word;
    overflow-wrap: anywhere;
}
.variant-price-tag {
    display: block;
    font-size: 11px;
    color: var(--text-secondary, #64748b);
    margin-top: 1px;
}
.moq-badge {
    display: inline-block;
    font-size: 10px;
    font-weight: 700;
    color: #0088FF;
    background: rgba(0, 136, 255, 0.08);
    border: 1px solid rgba(0, 136, 255, 0.2);
    border-radius: 20px;
    padding: 1px 7px;
    margin-top: 3px;
    letter-spacing: 0.02em;
}
.moq-warning {
    width: 100%;
    font-size: 11px;
    font-weight: 600;
    color: #ef4444;
    background: rgba(239, 68, 68, 0.06);
    border-left: 3px solid #ef4444;
    border-radius: 0 4px 4px 0;
    padding: 4px 10px;
    margin-top: 0;
}
/* +/- Controls */
.variant-qty-controls {
    display: flex;
    align-items: center;
    gap: 0;
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}
.qty-btn {
    width: 36px;
    height: 36px;
    border: none;
    background: var(--bg-surface, #f8fafc);
    color: var(--text-primary, #0f172a);
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.15s, color 0.15s;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
    user-select: none;
}
.qty-btn:hover {
    background: #0088FF;
    color: #fff;
}
.qty-btn.qty-minus:hover {
    background: #ef4444;
    color: #fff;
}
.variant-qty-input {
    width: 60px;
    height: 36px;
    border: none;
    border-left: 1px solid var(--border-color, #e2e8f0);
    border-right: 1px solid var(--border-color, #e2e8f0);
    text-align: center;
    font-size: 14px;
    font-weight: 700;
    color: var(--text-primary, #0f172a);
    background: white;
    outline: none;
    cursor: text;
    -moz-appearance: textfield;
}
.variant-qty-input::-webkit-outer-spin-button,
.variant-qty-input::-webkit-inner-spin-button { -webkit-appearance: none; }

/* Order Summary Bar */
.variant-order-summary {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 14px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-top: 20px;
    flex-wrap: wrap;
}
.order-summary-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.summary-label {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.summary-total {
    font-size: 16px;
    font-weight: 800;
    color: #ffffff;
    font-family: 'Montserrat', sans-serif;
}
.order-summary-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.btn-add-cart-variant {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    background: #ffffff;
    color: #0f172a;
    border: none;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
    font-family: 'Montserrat', sans-serif;
}
.btn-add-cart-variant:hover {
    background: var(--accent-orange, #f97316);
    color: white;
}
.btn-whatsapp-variant {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    background: #25D366;
    color: #ffffff;
    border: none;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
    font-family: 'Montserrat', sans-serif;
}
.btn-whatsapp-variant:hover {
    background: #128C7E;
}

/* ===== TOAST NOTIFICATION ===== */
#amh-toast-container {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 99999;
    display: flex;
    flex-direction: column;
    gap: 10px;
    pointer-events: none;
}
.amh-toast {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.18), 0 2px 8px rgba(0,0,0,0.08);
    padding: 14px 18px;
    min-width: 280px;
    max-width: 360px;
    pointer-events: all;
    transform: translateX(120%);
    opacity: 0;
    transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1), opacity 0.25s ease;
    border-left: 4px solid #ef4444;
    position: relative;
}
.amh-toast.amh-toast-success { border-left-color: #22c55e; }
.amh-toast.amh-toast-warning { border-left-color: #f59e0b; }
.amh-toast.amh-toast-info    { border-left-color: #0088FF; }
.amh-toast.amh-toast-show {
    transform: translateX(0);
    opacity: 1;
}
.amh-toast-icon {
    font-size: 20px;
    flex-shrink: 0;
    margin-top: 1px;
}
.amh-toast-body { flex: 1; }
.amh-toast-title {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    font-family: 'Montserrat', sans-serif;
    margin-bottom: 2px;
}
.amh-toast-msg {
    font-size: 12px;
    color: #64748b;
    line-height: 1.5;
}
.amh-toast-close {
    background: none;
    border: none;
    font-size: 16px;
    color: #94a3b8;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    flex-shrink: 0;
    transition: color 0.15s;
}
.amh-toast-close:hover { color: #334155; }

/* ===== MOQ VIOLATION POPUP ===== */
#amh-moq-popup {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 99998;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(6px);
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.amh-moq-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 0;
    max-width: 440px;
    width: 100%;
    box-shadow: 0 25px 80px rgba(0,0,0,0.25);
    overflow: hidden;
    transform: scale(0.88) translateY(20px);
    opacity: 0;
    transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1), opacity 0.25s ease;
}
#amh-moq-popup.show .amh-moq-card {
    transform: scale(1) translateY(0);
    opacity: 1;
}
.amh-moq-header {
    background: linear-gradient(135deg, #fef2f2 0%, #fff7ed 100%);
    padding: 24px 24px 16px;
    border-bottom: 1px solid #fee2e2;
    display: flex;
    align-items: flex-start;
    gap: 14px;
}
.amh-moq-header-icon {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, #ef4444, #f97316);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    flex-shrink: 0;
}
.amh-moq-header-text h4 {
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 4px;
}
.amh-moq-header-text p {
    font-size: 12px;
    color: #64748b;
    margin: 0;
    line-height: 1.5;
}
.amh-moq-body { padding: 18px 24px; }
.amh-moq-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
}
.amh-moq-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fef2f2;
    border: 1px solid #fee2e2;
    border-radius: 10px;
    padding: 10px 14px;
}
.amh-moq-item-icon {
    font-size: 16px;
    color: #ef4444;
    flex-shrink: 0;
}
.amh-moq-item-info { flex: 1; }
.amh-moq-item-name {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    font-family: 'Montserrat', sans-serif;
}
.amh-moq-item-detail {
    font-size: 11px;
    color: #ef4444;
    margin-top: 1px;
}
.amh-moq-actions {
    display: flex;
    gap: 10px;
}
.amh-moq-btn-ok {
    flex: 1;
    padding: 12px;
    background: linear-gradient(135deg, #ef4444, #f97316);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    font-family: 'Montserrat', sans-serif;
    transition: all 0.2s;
}
.amh-moq-btn-ok:hover { opacity: 0.9; transform: translateY(-1px); }
</style>

{{-- ===== TOAST CONTAINER ===== --}}
<div id="amh-toast-container"></div>

{{-- ===== MOQ VIOLATION POPUP ===== --}}
<div id="amh-moq-popup" style="display:none;" onclick="if(event.target===this) closeAmhMoqPopup()">
    <div class="amh-moq-card">
        <div class="amh-moq-header">
            <div class="amh-moq-header-icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="amh-moq-header-text">
                <h4>Minimum Order Quantity Not Met</h4>
                <p>Please update the quantity for the following variants to meet the minimum required order.</p>
            </div>
        </div>
        <div class="amh-moq-body">
            <div class="amh-moq-list" id="amh-moq-list"></div>
            <div class="amh-moq-actions">
                <button class="amh-moq-btn-ok" onclick="closeAmhMoqPopup()">
                    <i class="fa-solid fa-check"></i> Got it, I'll fix it
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== CUSTOMER DETAILS MODAL ===== --}}
<div id="variant-order-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.55); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:20px; padding:32px 28px; max-width:480px; width:100%; box-shadow:0 25px 60px rgba(0,0,0,0.25); position:relative;">
        <button onclick="closeVariantModal()" style="position:absolute;top:16px;right:18px;background:none;border:none;font-size:22px;cursor:pointer;color:#94a3b8;line-height:1;">✕</button>
        <h3 style="font-family:'Montserrat',sans-serif;font-size:18px;font-weight:800;margin:0 0 4px;">Complete Your Order</h3>
        <p style="font-size:13px;color:#64748b;margin:0 0 20px;">Enter your details to generate the invoice & send to WhatsApp</p>

        <form id="variant-order-form">
            @csrf
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div>
                    <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:5px;">Full Name *</label>
                    <input type="text" id="v-name" name="name" required placeholder="Your name" style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;box-sizing:border-box;">
                </div>
                <div>
                    <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:5px;">WhatsApp Number *</label>
                    <input type="tel" id="v-phone" name="phone" required placeholder="e.g. 9876543210" style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;box-sizing:border-box;">
                </div>
                <div>
                    <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:5px;">Delivery Address *</label>
                    <textarea id="v-address" name="address" required rows="2" placeholder="City, State, PIN Code" style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;resize:none;box-sizing:border-box;"></textarea>
                </div>
                <div>
                    <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:5px;">Notes (optional)</label>
                    <input type="text" id="v-notes" name="notes" placeholder="Any special instructions" style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;box-sizing:border-box;">
                </div>
            </div>
            <button type="button" id="v-submit-btn" onclick="submitVariantOrder()" style="margin-top:20px;width:100%;padding:13px;background:#25D366;color:#fff;border:none;border-radius:20px;font-size:14px;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-family:'Montserrat',sans-serif;transition:all 0.2s;">
                <i class="fa-brands fa-whatsapp"></i> Generate Invoice & Order on WhatsApp
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ===== CAROUSEL =====
    function detailCarousel(btn, direction) {
        const carousel = btn.closest('.detail-carousel');
        const images = carousel.querySelectorAll('.detail-carousel-main .details-main-img');
        const thumbs = carousel.querySelectorAll('.carousel-thumb');
        const counter = carousel.querySelector('.carousel-current');
        const total = images.length;
        let current = parseInt(carousel.dataset.current || 0);
        current = (current + direction + total) % total;
        carousel.dataset.current = current;
        images.forEach(img => img.classList.remove('active'));
        thumbs.forEach(t => t.classList.remove('active'));
        images[current].classList.add('active');
        if (thumbs[current]) thumbs[current].classList.add('active');
        if (counter) counter.textContent = current + 1;
    }

    function detailCarouselGoto(thumb, index) {
        const carousel = thumb.closest('.detail-carousel');
        const images = carousel.querySelectorAll('.detail-carousel-main .details-main-img');
        const thumbs = carousel.querySelectorAll('.carousel-thumb');
        const counter = carousel.querySelector('.carousel-current');
        carousel.dataset.current = index;
        images.forEach(img => img.classList.remove('active'));
        thumbs.forEach(t => t.classList.remove('active'));
        images[index].classList.add('active');
        thumbs[index].classList.add('active');
        if (counter) counter.textContent = index + 1;
    }

    // ===== VARIANT ORDER MODAL =====
    function openVariantOrderModal() {
        const selected = getSelectedVariants();
        if (selected.length === 0) {
            showToast('No Variant Selected', 'Please select at least one size/variant with quantity > 0', 'warning');
            return;
        }
        // MOQ validation
        const violations = getMoqViolations();
        if (violations.length > 0) {
            showMoqPopup(violations);
            return;
        }
        document.getElementById('variant-order-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeVariantModal() {
        document.getElementById('variant-order-modal').style.display = 'none';
        document.body.style.overflow = '';
    }
    // Close on backdrop click
    document.getElementById('variant-order-modal').addEventListener('click', function(e) {
        if (e.target === this) closeVariantModal();
    });

    async function submitVariantOrder() {
        const name    = document.getElementById('v-name').value.trim();
        const phone   = document.getElementById('v-phone').value.trim();
        const address = document.getElementById('v-address').value.trim();
        const notes   = document.getElementById('v-notes').value.trim();

        if (!name || !phone || !address) {
            showToast('Missing Details', 'Please fill in Name, WhatsApp Number and Address.', 'warning');
            return;
        }

        const selected = getSelectedVariants();
        if (selected.length === 0) {
            showToast('No Variants', 'No variants selected. Please go back and select quantities.', 'error');
            return;
        }

        const btn = document.getElementById('v-submit-btn');
        btn.disabled = true;
        btn.style.opacity = '0.7';
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating Invoice...';

        try {
            const response = await fetch('{{ route('cart.inquireVariants') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: PRODUCT_ID,
                    name:    name,
                    phone:   phone,
                    address: address,
                    notes:   notes,
                    variants: selected
                })
            });
            const data = await response.json();

            if (data.success) {
                // 1. Auto-download PDF invoice on user device
                const link = document.createElement('a');
                link.href = data.pdf_url;
                link.target = '_blank';
                link.download = '';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // 2. Open WhatsApp with PDF reference
                setTimeout(() => {
                    window.open(data.whatsapp_url, '_blank');
                }, 600);

                closeVariantModal();
            } else {
                showToast('Order Failed', data.message || 'Something went wrong. Please try again.', 'error');
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.innerHTML = '<i class="fa-brands fa-whatsapp"></i> Generate Invoice & Order on WhatsApp';
            }
        } catch (err) {
            showToast('Network Error', 'An error occurred. Please try again.', 'error');
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.innerHTML = '<i class="fa-brands fa-whatsapp"></i> Generate Invoice & Order on WhatsApp';
        }
    }

    // ===== VARIANT QTY LOGIC =====
    const PRODUCT_NAME = @json($product->name);
    const PRODUCT_PRICE = {{ $product->price }};
    const PRODUCT_SLUG = @json($product->slug);
    const PRODUCT_ID = {{ $product->id }};
    const CURRENCY = @json(env('CURRENCY_SYMBOL', '₹'));
    const WHATSAPP_NUMBER = @json(env('WHATSAPP_ADMIN_NUMBER', '917979747352'));

    /**
     * Check MOQ for a single input element.
     * Returns true if valid (qty=0 or qty>=min_qty), false if violation.
     */
    function checkMoqForInput(input) {
        const qty    = parseInt(input.value) || 0;
        const minQty = parseInt(input.dataset.minQty) || 1;
        const valueId   = input.dataset.valueId;
        const valueName = input.dataset.valueName;
        const warnEl = document.getElementById(`moq-warn-${valueId}`);

        const isViolation = qty > 0 && qty < minQty;

        // Visual feedback on input
        input.style.borderColor = isViolation ? '#ef4444' : '';
        input.style.color       = isViolation ? '#ef4444' : '';
        input.style.fontWeight  = isViolation ? '800'     : '';

        // Warning message below row
        if (warnEl) {
            if (isViolation) {
                warnEl.textContent = `⚠ Min. ${minQty} pcs required for "${valueName}"`;
                warnEl.style.display = 'block';
            } else {
                warnEl.style.display = 'none';
            }
        }
        return !isViolation;
    }

    /** Validate ALL inputs and return array of violations */
    function getMoqViolations() {
        const allInputs = document.querySelectorAll('.variant-qty-input');
        const violations = [];
        allInputs.forEach(inp => {
            const qty    = parseInt(inp.value) || 0;
            const minQty = parseInt(inp.dataset.minQty) || 1;
            if (qty > 0 && qty < minQty) {
                violations.push({ name: inp.dataset.valueName, minQty, qty });
            }
        });
        return violations;
    }

    function changeVariantQty(btn, delta) {
        const row = btn.closest('.variant-row');
        const input = row.querySelector('.variant-qty-input');
        const groupId = row.dataset.group;
        const minQty  = parseInt(row.dataset.minQty) || 1;

        let val = parseInt(input.value) || 0;
        val = Math.max(0, val + delta);

        // When clicking +, jump straight to min_qty if val would be 1..min_qty-1
        if (delta > 0 && val > 0 && val < minQty) {
            val = minQty;
        }

        input.value = val;
        checkMoqForInput(input);
        row.classList.toggle('has-qty', val > 0);
        updateGroupCount(groupId);
        updateOrderSummary();
    }

    // Called when user types directly into quantity input
    function onVariantQtyInput(input) {
        const row = input.closest('.variant-row');
        const groupId = input.dataset.group;
        let val = parseInt(input.value) || 0;
        if (val < 0) { input.value = 0; val = 0; }

        checkMoqForInput(input);
        row.classList.toggle('has-qty', val > 0);
        updateGroupCount(groupId);
        updateOrderSummary();
    }

    function updateGroupCount(groupId) {
        const inputs = document.querySelectorAll(`.variant-qty-input[data-group="${groupId}"]`);
        let total = 0;
        inputs.forEach(inp => { total += parseInt(inp.value) || 0; });
        const el = document.getElementById(`selected-count-${groupId}`);
        if (el) el.textContent = total;
    }

    function updateOrderSummary() {
        const allInputs = document.querySelectorAll('.variant-qty-input');
        let totalQty = 0;
        let totalPrice = 0;

        allInputs.forEach(inp => {
            const qty = parseInt(inp.value) || 0;
            const price = parseFloat(inp.dataset.price) || 0;
            totalQty += qty;
            totalPrice += qty * price;
        });

        const summaryBar = document.getElementById('variant-order-summary');
        const summaryText = document.getElementById('summary-total-text');

        if (totalQty > 0) {
            summaryBar.style.display = 'flex';
            summaryText.textContent = `${totalQty} piece${totalQty > 1 ? 's' : ''} · ${CURRENCY}${totalPrice.toLocaleString('en-IN', {minimumFractionDigits: 0})}`;
        } else {
            summaryBar.style.display = 'none';
        }
    }

    // Collect selected variants as [{value_id, value_name, qty, price}]
    function getSelectedVariants() {
        const allInputs = document.querySelectorAll('.variant-qty-input');
        const selected = [];
        allInputs.forEach(inp => {
            const qty = parseInt(inp.value) || 0;
            if (qty > 0) {
                selected.push({
                    value_id:   inp.dataset.valueId,
                    value_name: inp.dataset.valueName,
                    valueName:  inp.dataset.valueName,  // kept for WhatsApp flow
                    qty:   qty,
                    price: parseFloat(inp.dataset.price) || PRODUCT_PRICE,
                });
            }
        });
        return selected;
    }

    // ===== ADD VARIANTS TO CART (each size = separate cart line) =====
    async function addVariantsToCart() {
        const selected = getSelectedVariants();
        if (selected.length === 0) {
            showToast('No Variant Selected', 'Please select at least one size/variant with quantity > 0', 'warning');
            return;
        }
        // MOQ validation
        const violations = getMoqViolations();
        if (violations.length > 0) {
            showMoqPopup(violations);
            return;
        }

        const btn = document.querySelector('.btn-add-cart-variant');
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';
        }

        try {
            const response = await fetch('{{ route('cart.addVariants') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: PRODUCT_ID,
                    variants: selected
                })
            });
            const data = await response.json();

            if (data.success) {
                // Redirect to cart page
                window.location.href = data.redirect;
            } else {
                showToast('Cart Error', data.message || 'Failed to add to cart.', 'error');
                if (btn) {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.innerHTML = '<i class="fa-solid fa-bag-shopping"></i> Add to Cart';
                }
            }
        } catch (err) {
            showToast('Network Error', 'An error occurred. Please try again.', 'error');
            if (btn) {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.innerHTML = '<i class="fa-solid fa-bag-shopping"></i> Add to Cart';
            }
        }
    }

    // (WhatsApp flow is now handled via submitVariantOrder + modal above)

    // ===== BEAUTIFUL POPUP SYSTEM =====
    /**
     * Show animated toast notification
     * type: 'error' | 'success' | 'warning' | 'info'
     */
    function showToast(title, message, type = 'error', duration = 4000) {
        const container = document.getElementById('amh-toast-container');
        const icons = {
            error:   '<i class="fa-solid fa-circle-xmark" style="color:#ef4444"></i>',
            success: '<i class="fa-solid fa-circle-check" style="color:#22c55e"></i>',
            warning: '<i class="fa-solid fa-triangle-exclamation" style="color:#f59e0b"></i>',
            info:    '<i class="fa-solid fa-circle-info" style="color:#0088FF"></i>',
        };
        const toast = document.createElement('div');
        toast.className = `amh-toast amh-toast-${type === 'error' ? 'error' : type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info'}`;
        toast.innerHTML = `
            <div class="amh-toast-icon">${icons[type] || icons.error}</div>
            <div class="amh-toast-body">
                <div class="amh-toast-title">${title}</div>
                ${message ? `<div class="amh-toast-msg">${message}</div>` : ''}
            </div>
            <button class="amh-toast-close" onclick="this.closest('.amh-toast').remove()">✕</button>
        `;
        container.appendChild(toast);
        // Animate in
        requestAnimationFrame(() => {
            requestAnimationFrame(() => toast.classList.add('amh-toast-show'));
        });
        // Auto dismiss
        setTimeout(() => {
            toast.classList.remove('amh-toast-show');
            setTimeout(() => toast.remove(), 400);
        }, duration);
    }

    /** Show MOQ violation popup with detailed list */
    function showMoqPopup(violations) {
        const list = document.getElementById('amh-moq-list');
        list.innerHTML = violations.map(v => `
            <div class="amh-moq-item">
                <div class="amh-moq-item-icon"><i class="fa-solid fa-circle-exclamation"></i></div>
                <div class="amh-moq-item-info">
                    <div class="amh-moq-item-name">${v.name}</div>
                    <div class="amh-moq-item-detail">You entered ${v.qty} pcs &mdash; Min. required: <strong>${v.minQty} pcs</strong></div>
                </div>
            </div>
        `).join('');
        const popup = document.getElementById('amh-moq-popup');
        popup.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => popup.classList.add('show'));
        });
    }

    function closeAmhMoqPopup() {
        const popup = document.getElementById('amh-moq-popup');
        popup.classList.remove('show');
        setTimeout(() => {
            popup.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    }
</script>
@endsection
