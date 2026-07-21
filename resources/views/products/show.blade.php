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
                                    <div class="variant-row" data-group="{{ $group->id }}" data-value-id="{{ $val->id }}" data-value-name="{{ $val->value }}">
                                        <div class="variant-row-left">
                                            @if($val->color_hex)
                                                <span class="variant-color-dot" style="background:{{ $val->color_hex }};"></span>
                                            @endif
                                            <div>
                                                <span class="variant-name">{{ $val->value }}</span>
                                                <span class="variant-price-tag">{{ env('CURRENCY_SYMBOL', '₹') }}{{ number_format($product->price, 0) }} per piece</span>
                                            </div>
                                        </div>
                                        <div class="variant-qty-controls">
                                            <button type="button" class="qty-btn qty-minus" onclick="changeVariantQty(this, -1)" data-group="{{ $group->id }}">−</button>
                                            <input type="number" class="variant-qty-input" value="0" min="0"
                                                   data-group="{{ $group->id }}"
                                                   data-value-id="{{ $val->id }}"
                                                   data-value-name="{{ $val->value }}"
                                                   data-price="{{ $product->price }}"
                                                   oninput="onVariantQtyInput(this)">
                                            <button type="button" class="qty-btn qty-plus" onclick="changeVariantQty(this, 1)" data-group="{{ $group->id }}">+</button>
                                        </div>
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
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}
.variant-price-tag {
    display: block;
    font-size: 11px;
    color: var(--text-secondary, #64748b);
    margin-top: 1px;
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
</style>

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
            alert('Please select at least one size/variant with quantity > 0');
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
            alert('Please fill in all required fields (Name, Phone, Address).');
            return;
        }

        const selected = getSelectedVariants();
        if (selected.length === 0) {
            alert('No variants selected.');
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
                alert(data.message || 'Something went wrong. Please try again.');
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.innerHTML = '<i class="fa-brands fa-whatsapp"></i> Generate Invoice & Order on WhatsApp';
            }
        } catch (err) {
            alert('An error occurred. Please try again.');
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

    function changeVariantQty(btn, delta) {
        const row = btn.closest('.variant-row');
        const input = row.querySelector('.variant-qty-input');
        const groupId = row.dataset.group;

        let val = parseInt(input.value) || 0;
        val = Math.max(0, val + delta);
        input.value = val;

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
            alert('Please select at least one size/variant with quantity > 0');
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
                alert(data.message || 'Failed to add to cart.');
                if (btn) {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.innerHTML = '<i class="fa-solid fa-bag-shopping"></i> Add to Cart';
                }
            }
        } catch (err) {
            alert('An error occurred. Please try again.');
            if (btn) {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.innerHTML = '<i class="fa-solid fa-bag-shopping"></i> Add to Cart';
            }
        }
    }

    // (WhatsApp flow is now handled via submitVariantOrder + modal above)
</script>
@endsection
