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
                            <i class="fa-solid fa-bag-shopping"></i> Add to Cart
                        </button>
                    </form>
                @endif

                <!-- WhatsApp Direct Inquiry -->
                <a href="{{ route('cart.inquireSingle', $product->id) }}" class="btn-whatsapp" target="_blank">
                    <i class="fa-solid fa-message"></i> Inquire on WhatsApp
                </a>
            </div>
            
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
@endsection

@section('scripts')
<script>
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
</script>
@endsection
