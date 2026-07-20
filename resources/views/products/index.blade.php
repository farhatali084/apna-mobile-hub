@extends('layouts.app')

@section('title', 'Apna Mobile Hub - Elevate Your Mobile Experience')

@section('content')

@if(request('search') || request('category'))
    <!-- Search / Filter Results Grid -->
    <div class="container search-results-container">
        <h2 class="search-results-title">
            @if(request('search'))
                Search Results for "{{ request('search') }}"
            @elseif(request('category'))
                Category: {{ $currentCategory ? $currentCategory->name : request('category') }}
            @endif
        </h2>
        
        <div class="results-toolbar">
            <a href="{{ route('products.index') }}" class="back-to-home-btn">
                <i data-lucide="arrow-left"></i> Back to Homepage
            </a>
            
            <div class="category-filter-pills">
                <a href="{{ route('products.index', ['search' => request('search')]) }}" class="category-pill {{ !request('category') ? 'active' : '' }}">
                    All
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->slug, 'search' => request('search')]) }}" 
                       class="category-pill {{ request('category') == $cat->slug ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Two Column Grid for Filters Sidebar + Products Grid -->
        <div class="storefront-filter-layout">
            @if($currentCategory && $filterGroups->count() > 0)
                <!-- Left Sidebar Filters -->
                <aside class="filters-sidebar">
                    <form method="GET" action="{{ route('products.index') }}" id="sidebar-filters-form">
                        <!-- Maintain context parameters -->
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <!-- In Stock Filter -->
                        <div class="filter-section">
                            <h4 class="filter-section-title">Availability</h4>
                            <label class="filter-checkbox-label">
                                <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') == '1' ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>In Stock</span>
                            </label>
                        </div>

                        <!-- Dynamic Admin Defined Filter Groups -->
                        @foreach($filterGroups as $group)
                            <div class="filter-section">
                                <h4 class="filter-section-title">{{ $group->name }}</h4>
                                <div class="filter-options-list">
                                    @foreach($group->values as $val)
                                        <label class="filter-checkbox-label">
                                            <input type="checkbox" name="filters[]" value="{{ $val->id }}" {{ is_array(request('filters')) && in_array($val->id, request('filters')) ? 'checked' : '' }} onchange="this.form.submit()">
                                            @if($val->color_hex)
                                                <span class="color-dot" style="background-color: {{ $val->color_hex }}"></span>
                                            @endif
                                            <span>{{ $val->value }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <!-- Price Range Filter -->
                        <div class="filter-section">
                            <h4 class="filter-section-title">Price Range</h4>
                            <div class="price-range-inputs">
                                <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Min" class="price-input-box">
                                <span class="price-range-sep">-</span>
                                <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Max" class="price-input-box">
                            </div>
                            <button type="submit" class="btn-orange-sm apply-price-btn" style="margin-top: 10px; width: 100%;">Apply Filter</button>
                        </div>
                    </form>
                </aside>
            @endif

            <!-- Right Products Area -->
            <div class="products-results-area">
                @if($products->count() > 0)
                    <div class="results-grid-rz">
                        @foreach($products as $product)
                            <div class="product-card-rz">
                                <div class="product-card-img-wrapper">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}">
                                    </a>
                                </div>
                                <div class="product-card-info">
                                    <div class="product-card-title-row">
                                        <h3><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h3>
                                        <span class="rating"><i data-lucide="star" class="star-icon"></i> {{ number_format($product->rating, 1) }}</span>
                                    </div>
                                    <p class="product-card-desc">{{ $product->description }}</p>
                                    <div class="product-card-footer-rz" style="display: flex; flex-direction: column; gap: 10px; align-items: stretch; margin-top: auto; padding-top: 12px; border-top: 1px dashed var(--border-color);">
                                        <div class="price-row" style="display: flex; justify-content: space-between; align-items: center;">
                                            <span class="price" style="font-size: 16px; font-weight: 900; color: var(--text-primary);">{{ env('CURRENCY_SYMBOL', '₹') }}{{ number_format($product->price, 0) }}</span>
                                            <span class="original-price" style="font-size: 11px; text-decoration: line-through; color: #94a3b8;">{{ env('CURRENCY_SYMBOL', '₹') }}{{ number_format($product->price * 1.4, 0) }}</span>
                                        </div>
                                        <div class="card-action-btns" style="display: flex; gap: 8px; width: 100%;">
                                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="inline-form" style="flex: 1; margin: 0;">
                                                @csrf
                                                <button type="submit" class="btn-orange-sm" style="width: 100%; height: 36px; display: inline-flex; align-items: center; justify-content: center; text-align: center; white-space: nowrap; padding: 0 10px; font-size: 11px; box-sizing: border-box; border-radius: 20px;">Add To Cart</button>
                                            </form>
                                            <a href="{{ route('cart.inquireSingle', $product->id) }}" class="btn-wa-sm" target="_blank" style="flex: 1; height: 36px; display: inline-flex; align-items: center; justify-content: center; text-align: center; white-space: nowrap; padding: 0 10px; font-size: 11px; box-sizing: border-box; border-radius: 20px; gap: 4px;">
                                                <i data-lucide="message-circle" style="width: 12px; height: 12px;"></i> WhatsApp
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="pagination-container-rz">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="empty-state-rz">
                        <i data-lucide="search-x" class="empty-icon"></i>
                        <h3>No Products Found</h3>
                        <p>We couldn't find any products matching your query.</p>
                        <a href="{{ route('products.index') }}" class="btn-orange">Browse All Products</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@else
    <!-- 1. Hero Section -->
    <section class="hero-premium-light">
        <div class="hero-bg-layer-2"></div>
        <div class="hero-bg-layer-3-halo js-parallax-glow"></div>
        <div class="hero-bg-layer-4-vignette"></div>
        <div class="hero-bg-layer-5-grain"></div>
        
        <div class="hero-premium-container">
            <div class="hero-grid-wrapper">
                <!-- Left Content Column -->
                <div class="hero-left-content">
                    <!-- Trust Badge -->
                    <div class="hero-trust-badge-premium entrance-fade-up" style="animation-delay: 150ms;">
                        <div class="avatar-stack-premium">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=60&q=80" alt="Retailer Partner">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&q=80" alt="Retailer Partner">
                            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=60&q=80" alt="Retailer Partner">
                        </div>
                        <div class="trust-info-text">
                            <span style="color: #fbbf24; font-size: 13px;">★★★★★ <span style="color: #0F172A; margin-left: 4px; font-weight: 800;">4.9/5</span></span>
                            <span style="color: #64748B; font-size: 11px; font-weight: 600; margin-top: 1px;">Trusted by 2,000+ Retailers</span>
                        </div>
                    </div>

                    <!-- Headline -->
                    <h1 class="hero-title-premium js-parallax-heading">
                        <span class="text-reveal-container">
                            <span class="text-reveal-item" style="animation-delay: 300ms;">Wholesale Mobile</span>
                        </span>
                        <span class="text-reveal-container">
                            <span class="text-reveal-item" style="animation-delay: 450ms;">Accessories <span class="blue-grad-text">For Businesses</span></span>
                        </span>
                    </h1>

                    <!-- Description -->
                    <p class="hero-para-premium entrance-fade-up" style="animation-delay: 600ms;">
                        India's trusted B2B partner for premium cases, GaN fast chargers, and durable cables at certified factory-direct prices.
                    </p>

                    <!-- Actions -->
                    <div class="hero-btn-container-premium entrance-fade-up" style="animation-delay: 750ms;">
                        <a href="#deals" class="btn-premium-primary">
                            Shop Now <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
                        </a>
                        <a href="{{ route('contact') }}" class="btn-premium-secondary">
                            Bulk Enquiry <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
                        </a>
                    </div>

                 
                </div>

                <!-- Right Product Collage Column -->
                <div class="hero-right-composition entrance-scale-up" style="animation-delay: 950ms;">
                    <div class="pedestal-3d"></div>
                    <div class="neon-ring"></div>
                    
                    <div class="product-group-float js-parallax-products">
                        <!-- Top Right USB-C Cable -->
                        <img src="/images/cat_cables.png" 
                             alt="USB-C Cable" 
                             class="floating-product cable-top"
                             loading="eager"
                             fetchpriority="high"
                             decoding="async"
                             width="130"
                             height="130">

                        <!-- Left Bottom Power Bank -->
                        <img src="/images/cat_power_banks.png" 
                             alt="Power Bank" 
                             class="floating-product powerbank-left"
                             loading="eager"
                             fetchpriority="high"
                             decoding="async"
                             width="105"
                             height="105">

                        <!-- Bottom Right GaN Charger -->
                        <img src="/images/cat_chargers.png" 
                             alt="GaN Charger" 
                             class="floating-product charger-right"
                             loading="eager"
                             fetchpriority="high"
                             decoding="async"
                             width="105"
                             height="105">

                        <!-- Front Bottom AirPods -->
                        <img src="/images/category_airpods.png" 
                             alt="AirPods" 
                             class="floating-product airpods-front"
                             loading="eager"
                             fetchpriority="high"
                             decoding="async"
                             width="100"
                             height="100">

                        <!-- Center iPhone -->
                        <img src="/images/category_iphones.png" 
                             alt="iPhone" 
                             class="floating-product iphone-center"
                             loading="eager"
                             fetchpriority="high"
                             decoding="async"
                             width="190"
                             height="260">
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-transition-fade"></div>
    </section>

    <!-- 1.5 B2B Trust Metrics Strip -->
    <section class="metrics-strip-rz">
        <div class="metrics-container-rz">
            <div class="metric-item-rz">
                <span class="metric-icon">📦</span>
                <span class="metric-number" data-target="5000">0</span>
                <span class="metric-desc">Products</span>
            </div>
            <div class="metric-item-rz">
                <span class="metric-icon">🏪</span>
                <span class="metric-number" data-target="2000">0</span>
                <span class="metric-desc">Retailers</span>
            </div>
            <div class="metric-item-rz">
                <span class="metric-icon">🏷️</span>
                <span class="metric-number" data-target="25">0</span>
                <span class="metric-desc">Brands</span>
            </div>
            <div class="metric-item-rz">
                <span class="metric-icon">⭐</span>
                <span class="metric-number" data-target="99">0</span>
                <span class="metric-desc">Positive Reviews</span>
            </div>
        </div>
    </section>

    <!-- 2. Categories Grid & Promo Deals Banner -->
    <section class="categories-section-rz container" id="categories">
        <div class="categories-layout-grid">
            <!-- Left Categories Grid -->
            <div>
                <div class="section-title-wrapper">
                    <h2 class="category-section-title">Shop by <span class="orange-text">Category</span></h2>
                    <a href="{{ route('products.index', ['category' => 'phone-cases']) }}" class="view-all-cats">View all Categories <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i></a>
                </div>
                <div class="categories-grid">
                    @php
                        $mockupCats = [
                            ['name' => 'Chargers', 'slug' => 'chargers', 'img' => '/images/cat_chargers.png'],
                            ['name' => 'Cables', 'slug' => 'cables', 'img' => '/images/cat_cables.png'],
                            ['name' => 'Earphones', 'slug' => 'earphones', 'img' => '/images/cat_earphones.png'],
                            ['name' => 'Power Banks', 'slug' => 'power-banks', 'img' => '/images/cat_power_banks.png'],
                            ['name' => 'Phone Cases', 'slug' => 'phone-cases', 'img' => '/images/product_iphone_case.png'],
                            ['name' => 'Screen Protectors', 'slug' => 'screen-protectors', 'img' => '/images/cat_screen_protectors.png'],
                            ['name' => 'Car Accessories', 'slug' => 'car-accessories', 'img' => '/images/cat_car_accessories.png'],
                            ['name' => 'Smart Watches', 'slug' => 'smart-watches', 'img' => '/images/product_watch.png'],
                        ];
                    @endphp
                    @foreach($mockupCats as $c)
                        <a href="{{ route('products.index', ['category' => $c['slug']]) }}" class="reveal-slide-up category-grid-card">
                            <div class="category-card-img-box">
                                <img src="{{ $c['img'] }}" alt="{{ $c['name'] }}">
                            </div>
                            <span class="category-card-name">{{ $c['name'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            
            <!-- Right Promo Deals Card -->
            <div class="reveal-slide-up promo-deals-card">
                <div class="promo-card-glow"></div>
                <div class="promo-card-content">
                    <span class="promo-limited-badge"><i data-lucide="zap" style="width: 10px; height: 10px; fill: #fbbf24; stroke: none;"></i> Limited Time Offer</span>
                    <h3 class="promo-title">Bulk Deals <br>For Businesses</h3>
                    <div class="promo-bullet-list">
                        <span><i data-lucide="check-circle-2" style="width: 12px; height: 12px; color: var(--accent-orange);"></i> Best Prices</span>
                        <span><i data-lucide="check-circle-2" style="width: 12px; height: 12px; color: var(--accent-orange);"></i> Fast Delivery</span>
                        <span><i data-lucide="check-circle-2" style="width: 12px; height: 12px; color: var(--accent-orange);"></i> Trusted Quality</span>
                    </div>
                    <a href="{{ route('products.index', ['category' => 'chargers']) }}" class="btn-explore-deals">Explore Deals <i data-lucide="arrow-right" style="width: 12px; height: 12px;"></i></a>
                </div>
                
                <div class="promo-boxes-graphic">
                    <img src="/images/promo_boxes.png" alt="AMH Wholesale Boxes">
                    <div class="promo-discount-badge">
                        <span class="badge-up-to">Up to</span>
                        <span class="badge-percent">25%</span>
                        <span class="badge-off">Off</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Top Deals Section (Auto-Scrolling Slider) -->
    <section class="deals-section-rz container" id="deals" style="padding: 40px 0 60px;">
        <div class="section-title-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="category-section-title" style="margin: 0;">Top Deals <span style="font-size: 20px;">🔥</span></h2>
            <div style="display: flex; gap: 10px; align-items: center;">
                <button id="deals-prev" class="slider-arrow-btn" style="width: 40px; height: 40px; border-radius: 50%; background-color: #fff; border: 1px solid var(--border-color); color: var(--text-primary); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition);"><i data-lucide="chevron-left" style="width: 16px; height: 16px;"></i></button>
                <button id="deals-next" class="slider-arrow-btn" style="width: 40px; height: 40px; border-radius: 50%; background-color: #fff; border: 1px solid var(--border-color); color: var(--text-primary); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition);"><i data-lucide="chevron-right" style="width: 16px; height: 16px;"></i></button>
            </div>
        </div>
        
        <div id="deals-slider-container" style="overflow-x: auto; scroll-behavior: smooth; display: flex; gap: 20px; scrollbar-width: none; -ms-overflow-style: none; padding-bottom: 10px;">
            @foreach($products as $product)
                <div class="product-card-rz deals-product-card" style="flex: 0 0 280px; width: 280px; background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; padding: 14px; display: flex; flex-direction: column; position: relative; transition: all 0.3s; box-shadow: var(--shadow-sm);" onmouseover="this.style.borderColor='var(--accent-orange)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.transform='translateY(0)'">
                    <div class="wishlist-badge-wrapper" style="position: absolute; right: 12px; top: 12px; z-index: 10;">
                        <button class="wishlist-btn" style="border: none; background-color: var(--bg-surface); width: 28px; height: 28px; border-radius: 50%; box-shadow: 0 2px 6px rgba(0,0,0,0.06); display: flex; align-items: center; justify-content: center; cursor: pointer; color: #94a3b8; transition: color 0.2s;" onmouseover="this.style.color='red'" onmouseout="this.style.color='#94a3b8'">
                            <i data-lucide="heart" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                    
                    <div class="product-card-img-wrapper" style="background-color: var(--bg-primary); border-radius: 12px; height: 220px; overflow: hidden; position: relative;">
                        <a href="{{ route('products.show', $product->slug) }}" style="width: 100%; height: 100%; display: block;">
                            <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;">
                        </a>
                    </div>
                    
                    <div class="product-card-info" style="margin-top: 14px; display: flex; flex-direction: column; gap: 6px; flex-grow: 1;">
                        <h3 style="font-size: 14px; font-weight: 800; line-height: 1.35; font-family: 'Montserrat', sans-serif; margin: 0;"><a href="{{ route('products.show', $product->slug) }}" style="color: var(--text-primary); text-decoration: none;">{{ $product->name }}</a></h3>
                        
                        <div class="price-row-badge" style="display: flex; align-items: center; gap: 8px; margin-top: auto; padding-top: 10px; border-top: 1px dashed var(--border-color);">
                            <span class="price-text" style="font-size: 15px; font-weight: 900; color: var(--text-primary);">{{ env('CURRENCY_SYMBOL', '₹') }}{{ number_format($product->price, 0) }}</span>
                            <span class="original-price" style="font-size: 11px; text-decoration: line-through; color: #94a3b8;">{{ env('CURRENCY_SYMBOL', '₹') }}{{ number_format($product->price * 1.4, 0) }}</span>
                            <span class="discount-percent-badge" style="font-size: 10px; font-weight: 800; background-color: rgba(239, 68, 68, 0.08); color: #ef4444; padding: 2px 6px; border-radius: 4px;">-30%</span>
                        </div>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" style="margin-top: 6px;">
                            @csrf
                            <button type="submit" class="btn-orange-sm" style="width: 100%; border-radius: 20px; padding: 8px 12px; font-size: 11px; text-transform: uppercase;">Add to Cart</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 3.5 Top Brands Section (Auto-Scrolling Slider) -->
    <section class="brands-section-rz container" id="top-brands" style="padding: 0 0 60px;">
        <div class="section-title-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="category-section-title" style="margin: 0;">Top Brands <span style="font-size: 20px;">⭐</span></h2>
            <div style="display: flex; gap: 10px; align-items: center;">
                <button id="brands-prev" class="slider-arrow-btn" style="width: 40px; height: 40px; border-radius: 50%; background-color: #fff; border: 1px solid var(--border-color); color: var(--text-primary); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition);"><i data-lucide="chevron-left" style="width: 16px; height: 16px;"></i></button>
                <button id="brands-next" class="slider-arrow-btn" style="width: 40px; height: 40px; border-radius: 50%; background-color: #fff; border: 1px solid var(--border-color); color: var(--text-primary); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition);"><i data-lucide="chevron-right" style="width: 16px; height: 16px;"></i></button>
            </div>
        </div>
        
        <div id="brands-slider-container" style="overflow-x: auto; scroll-behavior: smooth; display: flex; gap: 20px; scrollbar-width: none; -ms-overflow-style: none; padding-bottom: 10px;">
            @php
                $brandList = ['ANKER', 'boAt', 'PORTRONICS', 'UGREEN', 'spigen', 'ESR', 'SAMSUNG', 'realme', 'mi', 'OnePlus', 'Apple', 'Xiaomi'];
            @endphp
            @foreach($brandList as $bName)
                <div class="brand-card-rz" style="flex: 0 0 200px; width: 200px; background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; padding: 20px; display: flex; align-items: center; justify-content: center; height: 100px; transition: all 0.3s;" onmouseover="this.style.borderColor='var(--accent-orange)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.transform='translateY(0)'">
                    <span style="font-size: 16px; font-weight: 900; font-family: 'Montserrat', sans-serif; color: var(--text-primary); letter-spacing: -0.5px; text-transform: uppercase;">{{ $bName }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 3.7 Best Sellers Section (Auto-Scrolling Slider) -->
    <section class="deals-section-rz container" id="best-sellers" style="padding: 0 0 60px;">
        <div class="section-title-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="category-section-title" style="margin: 0;">Best Sellers <span style="font-size: 20px;">🏆</span></h2>
            <div style="display: flex; gap: 10px; align-items: center;">
                <button id="bestsellers-prev" class="slider-arrow-btn" style="width: 40px; height: 40px; border-radius: 50%; background-color: #fff; border: 1px solid var(--border-color); color: var(--text-primary); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition);"><i data-lucide="chevron-left" style="width: 16px; height: 16px;"></i></button>
                <button id="bestsellers-next" class="slider-arrow-btn" style="width: 40px; height: 40px; border-radius: 50%; background-color: #fff; border: 1px solid var(--border-color); color: var(--text-primary); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition);"><i data-lucide="chevron-right" style="width: 16px; height: 16px;"></i></button>
            </div>
        </div>
        
        <div id="bestsellers-slider-container" style="overflow-x: auto; scroll-behavior: smooth; display: flex; gap: 20px; scrollbar-width: none; -ms-overflow-style: none; padding-bottom: 10px;">
            @foreach($products->shuffle() as $product)
                <div class="product-card-rz deals-product-card" style="flex: 0 0 280px; width: 280px; background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; padding: 14px; display: flex; flex-direction: column; position: relative; transition: all 0.3s; box-shadow: var(--shadow-sm);" onmouseover="this.style.borderColor='var(--accent-orange)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.transform='translateY(0)'">
                    <div class="wishlist-badge-wrapper" style="position: absolute; right: 12px; top: 12px; z-index: 10;">
                        <button class="wishlist-btn" style="border: none; background-color: var(--bg-surface); width: 28px; height: 28px; border-radius: 50%; box-shadow: 0 2px 6px rgba(0,0,0,0.06); display: flex; align-items: center; justify-content: center; cursor: pointer; color: #94a3b8; transition: color 0.2s;" onmouseover="this.style.color='red'" onmouseout="this.style.color='#94a3b8'">
                            <i data-lucide="heart" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                    
                    <div class="product-card-img-wrapper" style="background-color: var(--bg-primary); border-radius: 12px; height: 220px; overflow: hidden; position: relative;">
                        <a href="{{ route('products.show', $product->slug) }}" style="width: 100%; height: 100%; display: block;">
                            <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;">
                        </a>
                    </div>
                    
                    <div class="product-card-info" style="margin-top: 14px; display: flex; flex-direction: column; gap: 6px; flex-grow: 1;">
                        <h3 style="font-size: 14px; font-weight: 800; line-height: 1.35; font-family: 'Montserrat', sans-serif; margin: 0;"><a href="{{ route('products.show', $product->slug) }}" style="color: var(--text-primary); text-decoration: none;">{{ $product->name }}</a></h3>
                        
                        <div class="price-row-badge" style="display: flex; align-items: center; gap: 8px; margin-top: auto; padding-top: 10px; border-top: 1px dashed var(--border-color);">
                            <span class="price-text" style="font-size: 15px; font-weight: 900; color: var(--text-primary);">{{ env('CURRENCY_SYMBOL', '₹') }}{{ number_format($product->price, 0) }}</span>
                            <span class="original-price" style="font-size: 11px; text-decoration: line-through; color: #94a3b8;">{{ env('CURRENCY_SYMBOL', '₹') }}{{ number_format($product->price * 1.4, 0) }}</span>
                            <span class="discount-percent-badge" style="font-size: 10px; font-weight: 800; background-color: rgba(239, 68, 68, 0.08); color: #ef4444; padding: 2px 6px; border-radius: 4px;">-30%</span>
                        </div>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" style="margin-top: 6px;">
                            @csrf
                            <button type="submit" class="btn-orange-sm" style="width: 100%; border-radius: 20px; padding: 8px 12px; font-size: 11px; text-transform: uppercase;">Add to Cart</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 3.9 Dual Promotional Banners Section -->
    <section class="container" style="padding: 0 0 60px;">
        <div class="promo-banners-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- Left Banner -->
            <div class="reveal-slide-left promo-banner-card-rz" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 20px; padding: 40px; color: #ffffff; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; min-height: 220px; box-shadow: var(--shadow-md);">
                <div style="position: absolute; right: -20px; bottom: -20px; width: 140px; height: 140px; border-radius: 50%; background-color: var(--accent-orange); opacity: 0.15; filter: blur(20px);"></div>
                <div>
                    <span style="color: var(--accent-orange); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px;">SUPER VALUE DEALS</span>
                    <h3 style="font-size: 28px; font-weight: 900; font-family: 'Montserrat', sans-serif; line-height: 1.2; margin: 10px 0 15px; text-transform: uppercase;">Premium Chargers <br><span style="color: var(--accent-orange);">Up to 40% Off</span></h3>
                    <p style="font-size: 13px; color: #94a3b8; margin: 0 0 20px; max-width: 320px;">Stock up your store with high demand GaN fast chargers and adapters.</p>
                </div>
                <a href="{{ route('products.index', ['category' => 'chargers']) }}" class="btn-orange" style="width: fit-content; border-radius: 20px; padding: 10px 24px; font-size: 12px; font-weight: 800; text-decoration: none;">Order Bulk Now <i data-lucide="arrow-right" style="width: 14px; height: 14px; margin-left: 4px;"></i></a>
            </div>

            <!-- Right Banner -->
            <div class="reveal-slide-right promo-banner-card-rz" style="background: linear-gradient(135deg, #005eff 0%, #0036a3 100%); border-radius: 20px; padding: 40px; color: #ffffff; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; min-height: 220px; box-shadow: var(--shadow-md);">
                <div style="position: absolute; right: -20px; bottom: -20px; width: 140px; height: 140px; border-radius: 50%; background-color: #ffffff; opacity: 0.15; filter: blur(20px);"></div>
                <div>
                    <span style="color: #fbbf24; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px;">EXCLUSIVE OFFER</span>
                    <h3 style="font-size: 28px; font-weight: 900; font-family: 'Montserrat', sans-serif; line-height: 1.2; margin: 10px 0 15px; text-transform: uppercase;">Unbreakable Cables <br><span style="color: #fbbf24;">Buy 10 Get 2 Free</span></h3>
                    <p style="font-size: 13px; color: #cbd5e1; margin: 0 0 20px; max-width: 320px;">Top-rated braided Type-C & Lightning cables with lifetime durability.</p>
                </div>
                <a href="{{ route('products.index', ['category' => 'cables']) }}" class="btn-orange" style="background-color: #ffffff; color: #005eff; width: fit-content; border-radius: 20px; padding: 10px 24px; font-size: 12px; font-weight: 800; text-decoration: none;" onmouseover="this.style.backgroundColor='var(--accent-orange)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='#ffffff'; this.style.color='#005eff'">Claim Offer <i data-lucide="arrow-right" style="width: 14px; height: 14px; margin-left: 4px;"></i></a>
            </div>
        </div>
    </section>

    <!-- 3.11 Most Demanded Products Section (Auto-Scrolling Slider) -->
    <section class="deals-section-rz container" id="most-demanded" style="padding: 0 0 60px;">
        <div class="section-title-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 class="category-section-title" style="margin: 0;">Most Demanded Products <span style="font-size: 20px;">🔥</span></h2>
            <div style="display: flex; gap: 10px; align-items: center;">
                <button id="demanded-prev" class="slider-arrow-btn" style="width: 40px; height: 40px; border-radius: 50%; background-color: #fff; border: 1px solid var(--border-color); color: var(--text-primary); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition);"><i data-lucide="chevron-left" style="width: 16px; height: 16px;"></i></button>
                <button id="demanded-next" class="slider-arrow-btn" style="width: 40px; height: 40px; border-radius: 50%; background-color: #fff; border: 1px solid var(--border-color); color: var(--text-primary); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition);"><i data-lucide="chevron-right" style="width: 16px; height: 16px;"></i></button>
            </div>
        </div>
        
        <div id="demanded-slider-container" style="overflow-x: auto; scroll-behavior: smooth; display: flex; gap: 20px; scrollbar-width: none; -ms-overflow-style: none; padding-bottom: 10px;">
            @foreach($products->shuffle() as $product)
                <div class="product-card-rz deals-product-card" style="flex: 0 0 280px; width: 280px; background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; padding: 14px; display: flex; flex-direction: column; position: relative; transition: all 0.3s; box-shadow: var(--shadow-sm);" onmouseover="this.style.borderColor='var(--accent-orange)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='var(--border-color)'; this.style.transform='translateY(0)'">
                    <div class="wishlist-badge-wrapper" style="position: absolute; right: 12px; top: 12px; z-index: 10;">
                        <button class="wishlist-btn" style="border: none; background-color: var(--bg-surface); width: 28px; height: 28px; border-radius: 50%; box-shadow: 0 2px 6px rgba(0,0,0,0.06); display: flex; align-items: center; justify-content: center; cursor: pointer; color: #94a3b8; transition: color 0.2s;" onmouseover="this.style.color='red'" onmouseout="this.style.color='#94a3b8'">
                            <i data-lucide="heart" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                    
                    <div class="product-card-img-wrapper" style="background-color: var(--bg-primary); border-radius: 12px; height: 220px; overflow: hidden; position: relative;">
                        <a href="{{ route('products.show', $product->slug) }}" style="width: 100%; height: 100%; display: block;">
                            <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;">
                        </a>
                    </div>
                    
                    <div class="product-card-info" style="margin-top: 14px; display: flex; flex-direction: column; gap: 6px; flex-grow: 1;">
                        <h3 style="font-size: 14px; font-weight: 800; line-height: 1.35; font-family: 'Montserrat', sans-serif; margin: 0;"><a href="{{ route('products.show', $product->slug) }}" style="color: var(--text-primary); text-decoration: none;">{{ $product->name }}</a></h3>
                        
                        <div class="price-row-badge" style="display: flex; align-items: center; gap: 8px; margin-top: auto; padding-top: 10px; border-top: 1px dashed var(--border-color);">
                            <span class="price-text" style="font-size: 15px; font-weight: 900; color: var(--text-primary);">{{ env('CURRENCY_SYMBOL', '₹') }}{{ number_format($product->price, 0) }}</span>
                            <span class="original-price" style="font-size: 11px; text-decoration: line-through; color: #94a3b8;">{{ env('CURRENCY_SYMBOL', '₹') }}{{ number_format($product->price * 1.4, 0) }}</span>
                            <span class="discount-percent-badge" style="font-size: 10px; font-weight: 800; background-color: rgba(239, 68, 68, 0.08); color: #ef4444; padding: 2px 6px; border-radius: 4px;">-30%</span>
                        </div>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" style="margin-top: 6px;">
                            @csrf
                            <button type="submit" class="btn-orange-sm" style="width: 100%; border-radius: 20px; padding: 8px 12px; font-size: 11px; text-transform: uppercase;">Add to Cart</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 4. Feature Icons Strip -->
    <div class="feature-icons-strip">
        <div class="container feature-strip-container">
            <div class="feature-strip-item">
                <i data-lucide="shield-check" style="width: 20px; height: 20px; color: var(--accent-orange);"></i>
                <div class="feature-strip-text">
                    <span class="title">Genuine Products</span>
                    <span class="desc">100% original & branded</span>
                </div>
            </div>
            <div class="feature-strip-item">
                <i data-lucide="lock" style="width: 20px; height: 20px; color: var(--accent-orange);"></i>
                <div class="feature-strip-text">
                    <span class="title">Secure Payments</span>
                    <span class="desc">Multiple safe payment options</span>
                </div>
            </div>
            <div class="feature-strip-item">
                <i data-lucide="refresh-cw" style="width: 20px; height: 20px; color: var(--accent-orange);"></i>
                <div class="feature-strip-text">
                    <span class="title">Easy Returns</span>
                    <span class="desc">7 days return policy</span>
                </div>
            </div>
            <div class="feature-strip-item">
                <i data-lucide="headphones" style="width: 20px; height: 20px; color: var(--accent-orange);"></i>
                <div class="feature-strip-text">
                    <span class="title">Dedicated Support</span>
                    <span class="desc">We're here to help you</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Stay Updated Newsletter Banner -->
    <section class="container newsletter-banner-section">
        <div class="reveal-slide-up newsletter-row">
            <div class="newsletter-left-col">
                <div class="newsletter-icon-box">
                    <i data-lucide="mail" style="width: 22px; height: 22px;"></i>
                </div>
                <div class="newsletter-text-box">
                    <h3>Stay Updated</h3>
                    <p>Subscribe to get latest offers & deals</p>
                </div>
            </div>
            <form action="#" onsubmit="event.preventDefault(); alert('Subscribed successfully!'); this.reset();" class="newsletter-form-box">
                <input type="email" placeholder="Enter your email" required class="newsletter-input">
                <button type="submit" class="btn-subscribe">Subscribe</button>
            </form>
        </div>
    </section>

    <!-- 6. Trusted by Leading Brands Strip -->
    <section class="container" id="brands" style="padding: 20px 0 60px; text-align: center;">
        <h4 class="brands-title-label">Trusted by Leading Brands</h4>
        <div class="brands-strip-container">
            <span>ANKER</span>
            <span>boAt</span>
            <span>PORTRONICS</span>
            <span>UGREEN</span>
            <span>spigen</span>
            <span>ESR</span>
            <span>SAMSUNG</span>
            <span>realme</span>
            <span>mi</span>
        </div>
    </section>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Slider/scroll controls helpers with auto scroll
        function setupSlider(sliderId, prevBtnId, nextBtnId, intervalMs = 4000) {
            const slider = document.getElementById(sliderId);
            const prev = document.getElementById(prevBtnId);
            const next = document.getElementById(nextBtnId);
            
            if (!slider) return;

            // Manual controls
            if (prev && next) {
                prev.addEventListener('click', () => {
                    slider.scrollBy({ left: -320, behavior: 'smooth' });
                    resetAutoScroll();
                });
                next.addEventListener('click', () => {
                    slider.scrollBy({ left: 320, behavior: 'smooth' });
                    resetAutoScroll();
                });
            }

            // Auto scroll logic
            let autoScrollInterval = setInterval(scrollNext, intervalMs);

            function scrollNext() {
                const maxScrollLeft = slider.scrollWidth - slider.clientWidth;
                // If near or at the end, wrap to start
                if (slider.scrollLeft >= maxScrollLeft - 15) {
                    slider.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    slider.scrollBy({ left: 320, behavior: 'smooth' });
                }
            }

            function resetAutoScroll() {
                clearInterval(autoScrollInterval);
                autoScrollInterval = setInterval(scrollNext, intervalMs);
            }

            // Pause auto-scroll on hover/touch
            slider.addEventListener('mouseenter', () => clearInterval(autoScrollInterval));
            slider.addEventListener('mouseleave', () => resetAutoScroll());
            slider.addEventListener('touchstart', () => clearInterval(autoScrollInterval), {passive: true});
            slider.addEventListener('touchend', () => resetAutoScroll(), {passive: true});
        }
        
        setupSlider('trending-slider', 'trending-prev', 'trending-next', 4000);
        setupSlider('featured-slider', 'featured-prev', 'featured-next', 4500);
        setupSlider('new-slider', 'new-prev', 'new-next', 5000);
        setupSlider('reviews-slider', 'reviews-prev', 'reviews-next', 6000);
        setupSlider('deals-slider-container', 'deals-prev', 'deals-next', 700);
        setupSlider('brands-slider-container', 'brands-prev', 'brands-next', 700);
        setupSlider('bestsellers-slider-container', 'bestsellers-prev', 'bestsellers-next', 700);
        setupSlider('demanded-slider-container', 'demanded-prev', 'demanded-next', 700);

        // --- B2B Premium Hero Parallax & Animations ---
        const isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        if (!isReducedMotion) {
            const hero = document.querySelector('.hero-premium-light');
            const background = document.querySelector('.hero-bg-layer-2');
            const glow = document.querySelector('.js-parallax-glow');
            const products = document.querySelector('.js-parallax-products');
            
            if (hero) {
                hero.addEventListener('mousemove', (e) => {
                    const rect = hero.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;
                    
                    // Apply extremely subtle translations matching the design specs:
                    // Background: 1px-3px, Glow: 5px, Product: 8px
                    if (background) {
                        background.style.transform = `translate3d(${x * 0.002}px, ${y * 0.002}px, 0)`;
                    }
                    if (glow) {
                        glow.style.transform = `translate3d(${x * 0.008}px, ${y * 0.008}px, 0) translateY(-50%)`;
                    }
                    if (products) {
                        products.style.transform = `translate3d(${x * 0.012}px, ${y * 0.012}px, 0)`;
                    }
                }, { passive: true });
                
                hero.addEventListener('mouseleave', () => {
                    if (background) background.style.transform = 'none';
                    if (glow) glow.style.transform = 'translateY(-50%)';
                    if (products) products.style.transform = 'none';
                });
            }
            
            // Scroll-based translations (Products move 18px upward, glow fades, heading moves 10px slower)
            window.addEventListener('scroll', () => {
                const scrolled = window.scrollY;
                if (scrolled < window.innerHeight) {
                    const heading = document.querySelector('.js-parallax-heading');
                    const productsWrap = document.querySelector('.hero-right-composition');
                    const glowLayer = document.querySelector('.js-parallax-glow');
                    const bgLayer = document.querySelector('.hero-bg-layer-2');
                    
                    if (productsWrap) {
                        const py = Math.min(scrolled * 0.08, 18);
                        productsWrap.style.transform = `translateY(${-py}px)`;
                    }
                    if (glowLayer) {
                        glowLayer.style.opacity = Math.max(0.2 - (scrolled * 0.0005), 0.05);
                    }
                    if (heading) {
                        const hy = scrolled * 0.03;
                        heading.style.transform = `translateY(${hy}px)`;
                    }
                    if (bgLayer) {
                        const by = scrolled * 0.015;
                        bgLayer.style.transform = `translateY(${by}px)`;
                    }
                }
            }, { passive: true });
        }

        // --- Trust Metrics Counter Animations ---
        const metrics = document.querySelectorAll('.metric-number');
        if (metrics.length > 0 && 'IntersectionObserver' in window) {
            const observerOptions = {
                threshold: 0.5,
                rootMargin: '0px 0px -50px 0px'
            };
            const metricsObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = entry.target;
                        const endVal = parseInt(target.getAttribute('data-target'), 10);
                        let startVal = 0;
                        const duration = 1500;
                        const startTime = performance.now();
                        
                        function animate(now) {
                            const elapsed = now - startTime;
                            const progress = Math.min(elapsed / duration, 1);
                            const ease = progress * (2 - progress); // easeOutQuad
                            const currentVal = Math.floor(startVal + (endVal - startVal) * ease);
                            
                            if (endVal === 99) {
                                target.innerText = currentVal.toString() + '%';
                            } else {
                                target.innerText = currentVal.toLocaleString() + '+';
                            }
                            
                            if (progress < 1) {
                                requestAnimationFrame(animate);
                            } else {
                                if (endVal === 99) {
                                    target.innerText = endVal.toString() + '%';
                                } else {
                                    target.innerText = endVal.toLocaleString() + '+';
                                }
                            }
                        }
                        requestAnimationFrame(animate);
                        observer.unobserve(target);
                    }
                });
            }, observerOptions);
            metrics.forEach(metric => metricsObserver.observe(metric));
        }
    });
</script>
@endsection
