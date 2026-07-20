<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $pageSeo->meta_title ?? 'Apna Mobile Hub - Wholesale Mobile Accessories For Businesses')</title>
    <meta name="description" content="@yield('meta_description', $pageSeo->meta_description ?? 'Apna Mobile Hub - India\'s trusted B2B wholesale partner for premium mobile cases, GaN fast chargers, cables, earphones, power banks & smart watches at factory-direct prices. Shop No. 456, Sanjay Market, Sakchi, Jamshedpur.')">
    <meta name="keywords" content="@yield('meta_keywords', $pageSeo->meta_keywords ?? 'mobile accessories wholesale, B2B mobile accessories, phone cases bulk, GaN chargers wholesale, USB cables bulk, earphones wholesale, power banks, smart watches, Jamshedpur, Sakchi, Apna Mobile Hub')">
    <meta name="author" content="Apna Mobile Hub">
    <meta name="robots" content="{{ $pageSeo->robots ?? 'index, follow' }}">
    <link rel="canonical" href="@yield('canonical', $pageSeo->canonical_url ?? url()->current())">
    <meta name="theme-color" content="#005EFF">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    {{-- Open Graph / Facebook / WhatsApp --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', $pageSeo->og_title ?? $pageSeo->meta_title ?? 'Apna Mobile Hub - Wholesale Mobile Accessories For Businesses')">
    <meta property="og:description" content="@yield('og_description', $pageSeo->og_description ?? $pageSeo->meta_description ?? 'India\'s trusted B2B wholesale partner for premium mobile accessories at factory-direct prices. GST Billing | Pan India Delivery | Bulk Pricing.')">
    <meta property="og:image" content="@yield('og_image', $pageSeo->og_image_url ?? asset('images/logo.png'))">
    <meta property="og:site_name" content="Apna Mobile Hub">
    <meta property="og:locale" content="en_IN">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', $pageSeo->og_title ?? $pageSeo->meta_title ?? 'Apna Mobile Hub - Wholesale Mobile Accessories For Businesses')">
    <meta name="twitter:description" content="@yield('og_description', $pageSeo->og_description ?? $pageSeo->meta_description ?? 'India\'s trusted B2B wholesale partner for premium mobile accessories at factory-direct prices.')">
    <meta name="twitter:image" content="@yield('og_image', $pageSeo->og_image_url ?? asset('images/logo.png'))">

    {{-- JSON-LD Structured Data: Organization + LocalBusiness --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "Apna Mobile Hub",
        "description": "India's trusted B2B wholesale partner for premium mobile cases, GaN fast chargers, cables, earphones, power banks & smart watches at factory-direct prices.",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "image": "{{ asset('images/logo.png') }}",
        "telephone": "+917979747352",
        "email": "Apnamobilehubjsr@gmail.com",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Shop No. 456, Sanjay Market, Sakchi",
            "addressLocality": "Jamshedpur",
            "addressRegion": "Jharkhand",
            "postalCode": "831001",
            "addressCountry": "IN"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "22.7876",
            "longitude": "86.2029"
        },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
            "opens": "10:00",
            "closes": "20:00"
        },
        "priceRange": "₹₹",
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.9",
            "reviewCount": "2000"
        },
        "sameAs": []
    }
    </script>

    @yield('structured_data')

    @if(isset($pageSeo) && $pageSeo && $pageSeo->schema_markup)
    <script type="application/ld+json">
    {!! $pageSeo->schema_markup !!}
    </script>
    @endif

    <!-- Google Fonts: Montserrat & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Montserrat:wght@400;500;700;800;900&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AlpineJS for interactive micro-effects -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css'])
    
    @yield('styles')
</head>

<body x-data="{ mobileMenuOpen: false }">
    <!-- Top Utility Bar -->
    <div class="top-utility-bar" style="background-color: #0B132B; color: #94A3B8; font-size: 11px; font-weight: 600; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05); font-family: 'Outfit', sans-serif;">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div class="top-bar-left" style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                <span style="display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-building" style="width: 14px; height: 14px; color: var(--accent-orange);"></i> B2B WHOLESALE SPECIALIST</span>
                <span style="display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-certificate" style="width: 14px; height: 14px; color: var(--accent-orange);"></i> Best Prices</span>
                <span style="display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-shield-halved" style="width: 14px; height: 14px; color: var(--accent-orange);"></i> Trusted Quality</span>
                <span style="display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-truck" style="width: 14px; height: 14px; color: var(--accent-orange);"></i> Reliable Supply</span>
            </div>
            <div class="top-bar-right">
                <a href="tel:+917979747352" style="color: #F8FAFC; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-phone" style="width: 13px; height: 13px; color: var(--accent-orange);"></i> Bulk Enquiry: +91 79797 47352
                </a>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <header class="navbar-container" style="position: sticky; top: 0; z-index: 1000; background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
        <div class="navbar-content container" style="height: 86px; display: flex; justify-content: space-between; align-items: center; gap: 20px;">
            
            <!-- Mobile Menu Toggle Button -->
            <button @click="mobileMenuOpen = true" class="mobile-menu-toggle" style="background: none; border: none; color: #0F172A; cursor: pointer; display: none; align-items: center; justify-content: center; padding: 8px; margin-left: -8px;">
                <i class="fa-solid fa-bars" style="width: 24px; height: 24px;"></i>
            </button>

            <!-- Brand Logo -->
            <a href="{{ route('products.index') }}" class="logo-link" style="display: flex; align-items: center; gap: 14px; text-decoration: none; white-space: nowrap;">
                <img src="/images/logo.png" alt="Apna Mobile Hub Logo" class="nav-logo-img">
                <div style="display: flex; flex-direction: column; line-height: 1.1;">
                    <span class="nav-logo-title">
                        Apna Mobile <span style="color: var(--accent-orange);">Hub</span>
                    </span>
                    <span class="nav-logo-subtitle logo-subtitle-hide">
                        YOUR TRUSTED B2B WHOLESALE PARTNER
                    </span>
                </div>
            </a>
            
            <!-- Navbar Nav Links -->
            <nav class="navbar-nav" style="display: flex; align-items: center; gap: 16px;">
                <a href="{{ route('products.index') }}" class="nav-btn {{ request()->routeIs('products.index') && !request('search') && !request('category') ? 'active' : '' }}">Home</a>
                <div class="nav-dropdown-wrapper" style="position: relative; display: inline-block;">
                    <a href="{{ route('products.index') }}#categories" class="nav-btn" style="display: flex; align-items: center; gap: 4px; text-transform: capitalize;">
                        Categories <i class="fa-solid fa-chevron-down" style="font-size: 10px;"></i>
                    </a>
                    <div class="nav-dropdown-menu">
                        @if(isset($footerCategories))
                            @foreach($footerCategories as $cat)
                                @if($cat->children && $cat->children->count() > 0)
                                    <div class="nav-dropdown-submenu-wrapper" style="position: relative;">
                                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="nav-dropdown-item" style="display: flex; justify-content: space-between; align-items: center;">
                                            {{ $cat->name }} <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i>
                                        </a>
                                        <div class="nav-dropdown-submenu">
                                            @foreach($cat->children as $child)
                                                <a href="{{ route('products.index', ['category' => $child->slug]) }}" class="nav-dropdown-item">{{ $child->name }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="nav-dropdown-item">{{ $cat->name }}</a>
                                @endif
                            @endforeach
                        @else
                            <a href="{{ route('products.index', ['category' => 'chargers']) }}" class="nav-dropdown-item">Chargers</a>
                            <a href="{{ route('products.index', ['category' => 'cables']) }}" class="nav-dropdown-item">Cables</a>
                            <a href="{{ route('products.index', ['category' => 'earphones']) }}" class="nav-dropdown-item">Earphones</a>
                        @endif
                    </div>
                </div>
                <a href="{{ route('products.index') }}#brands" class="nav-btn">Brands</a>
                <a href="{{ route('products.index') }}#deals" class="nav-btn">Deals</a>
                <!-- <a href="{{ route('products.index') }}#bulk-order" class="nav-btn">Bulk Order</a> -->
                <a href="{{ route('about') }}" class="nav-btn {{ request()->routeIs('about') ? 'active' : '' }}">About Us</a>
                <a href="{{ route('contact') }}" class="nav-btn {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
            </nav>
            
            <!-- Navbar Actions -->
            <div class="navbar-actions" style="display: flex; align-items: center; gap: 16px; flex-shrink: 0;">
                <form action="{{ route('products.index') }}" method="GET" class="desktop-search-form" style="margin: 0;">
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="text" name="search" placeholder="Search accessories..." value="{{ request('search') }}" style="width: 180px; padding: 10px 48px 10px 16px; border: 1px solid var(--border-color); border-radius: 20px; font-size: 13px; outline: none; background-color: var(--bg-surface); transition: all 0.3s;" onfocus="this.style.width='220px'; this.style.borderColor='var(--accent-orange)'" onblur="this.style.width='180px'; this.style.borderColor='var(--border-color)'">
                        <button type="submit" style="position: absolute; right: 4px; top: 4px; width: 32px; height: 32px; border-radius: 50%; border: none; background-color: var(--accent-orange); color: #ffffff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="fa-solid fa-magnifying-glass" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                </form>

                <!-- Cart Button -->
                <a href="{{ route('cart.index') }}" class="nav-icon-link" title="Shopping Cart" style="color: #0F172A; display: flex; align-items: center; position: relative; text-decoration: none;">
                    <i class="fa-solid fa-cart-shopping" class="nav-icon" style="width: 20px; height: 20px;"></i>
                    @php
                        $cartCount = 0;
                        if(session('cart')) {
                            foreach(session('cart') as $item) {
                                $cartCount += $item['quantity'];
                            }
                        }
                    @endphp
                    <span style="position: absolute; top: -6px; right: -6px; background-color: var(--accent-orange); color: #ffffff; font-size: 9px; font-weight: 800; width: 16px; height: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid #ffffff; line-height: 1;">{{ $cartCount }}</span>
                </a>
            </div>
        </div>

        <!-- Mobile Search Bar Row -->
        <div class="mobile-search-bar" style="display: none; padding: 0 16px 12px; background-color: #ffffff; border-bottom: 1px solid var(--border-color);">
            <form action="{{ route('products.index') }}" method="GET" style="margin: 0; width: 100%;">
                <div style="position: relative; display: flex; align-items: center; width: 100%;">
                    <input type="text" name="search" placeholder="Search accessories..." value="{{ request('search') }}" style="width: 100%; padding: 10px 48px 10px 16px; border: 1px solid var(--border-color); border-radius: 20px; font-size: 13px; outline: none; background-color: var(--bg-surface);">
                    <button type="submit" style="position: absolute; right: 4px; top: 4px; width: 32px; height: 32px; border-radius: 50%; border: none; background-color: var(--accent-orange); color: #ffffff; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-magnifying-glass" style="width: 14px; height: 14px;"></i>
                    </button>
                </div>
            </form>
        </div>
    </header>

    <!-- Mobile Navigation Drawer Overlay -->
    <div x-cloak 
         x-show="mobileMenuOpen" 
         class="mobile-sidebar-overlay"
         @click="mobileMenuOpen = false">
        
        <!-- Drawer Panel -->
        <div x-show="mobileMenuOpen"
             class="mobile-sidebar-panel"
             @click.stop>
            
            <!-- Drawer Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <img src="/images/logo.png" alt="Apna Mobile Hub Logo" style="height: 36px; width: auto;">
                    <span style="font-size: 14px; font-weight: 800; color: #0F172A; text-transform: uppercase; font-family: 'Montserrat', sans-serif;">Apna Mobile Hub</span>
                </div>
                <button @click="mobileMenuOpen = false" style="background: none; border: none; cursor: pointer; color: #64748B; padding: 6px;">
                    <i class="fa-solid fa-xmark" style="font-size: 20px;"></i>
                </button>
            </div>

            <!-- Drawer Links -->
            <div style="display: flex; flex-direction: column; gap: 10px; font-family: 'Outfit', sans-serif;">
                <a href="{{ route('products.index') }}" @click="mobileMenuOpen = false" style="font-size: 15px; font-weight: 700; color: #0F172A; text-decoration: none; padding: 10px 12px; border-radius: 8px; background-color: #F8FAFC; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-house" style="color: var(--accent-orange); width: 18px;"></i> Home
                </a>
                <a href="{{ route('products.index') }}#categories" @click="mobileMenuOpen = false" style="font-size: 15px; font-weight: 700; color: #0F172A; text-decoration: none; padding: 10px 12px; border-radius: 8px; background-color: #F8FAFC; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-layer-group" style="color: var(--accent-orange); width: 18px;"></i> Categories
                </a>
                <a href="{{ route('products.index') }}#brands" @click="mobileMenuOpen = false" style="font-size: 15px; font-weight: 700; color: #0F172A; text-decoration: none; padding: 10px 12px; border-radius: 8px; background-color: #F8FAFC; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-tag" style="color: var(--accent-orange); width: 18px;"></i> Brands
                </a>
                <a href="{{ route('products.index') }}#deals" @click="mobileMenuOpen = false" style="font-size: 15px; font-weight: 700; color: #0F172A; text-decoration: none; padding: 10px 12px; border-radius: 8px; background-color: #F8FAFC; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-fire" style="color: var(--accent-orange); width: 18px;"></i> Deals
                </a>
                <a href="{{ route('about') }}" @click="mobileMenuOpen = false" style="font-size: 15px; font-weight: 700; color: #0F172A; text-decoration: none; padding: 10px 12px; border-radius: 8px; background-color: #F8FAFC; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-circle-info" style="color: var(--accent-orange); width: 18px;"></i> About Us
                </a>
                <a href="{{ route('contact') }}" @click="mobileMenuOpen = false" style="font-size: 15px; font-weight: 700; color: #0F172A; text-decoration: none; padding: 10px 12px; border-radius: 8px; background-color: #F8FAFC; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-envelope" style="color: var(--accent-orange); width: 18px;"></i> Contact
                </a>
            </div>

            <!-- Drawer Utility Footer -->
            <div style="margin-top: auto; padding-top: 16px; border-top: 1px solid var(--border-color); font-size: 13px; font-weight: 600;">
                <a href="tel:+917979747352" style="color: #0F172A; text-decoration: none; display: flex; align-items: center; gap: 10px; padding: 10px; background: #FFF7ED; border-radius: 8px; border: 1px solid #FFEDD5;">
                    <i class="fa-solid fa-phone" style="color: var(--accent-orange); font-size: 16px;"></i>
                    <div>
                        <div style="font-size: 11px; color: #9A3412;">B2B Wholesale Helpline</div>
                        <div style="font-weight: 800; color: #7C2D12;">+91 79797 47352</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Toast Notifications -->
    @if(session('success'))
        <div class="toast success-toast" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
            <span class="toast-emoji">✨</span>
            <div class="toast-content">{{ session('success') }}</div>
            <button @click="show = false" class="toast-close">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="toast error-toast" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
            <span class="toast-emoji">⚠️</span>
            <div class="toast-content">{{ session('error') }}</div>
            <button @click="show = false" class="toast-close">&times;</button>
        </div>
    @endif

    <!-- Main Content Area -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-grid">
            <div class="footer-brand-col">
                <a href="{{ route('products.index') }}" class="logo-link" style="display: flex; align-items: center; gap: 12px; text-decoration: none; white-space: nowrap;">
                    <img src="/images/logo.png" alt="Apna Mobile Hub Logo" style="height: 52px; width: auto; object-fit: contain; border-radius: 8px;">
                    <div style="display: flex; flex-direction: column; line-height: 1.1;">
                        <span style="font-size: 20px; font-weight: 900; color: #0F172A; font-family: 'Montserrat', sans-serif; letter-spacing: -0.5px; text-transform: uppercase;">
                            Apna Mobile <span style="color: var(--accent-orange);">Hub</span>
                        </span>
                        <span style="font-size: 8px; font-weight: 800; color: #64748B; letter-spacing: 0.5px; text-transform: uppercase; margin-top: 2px;">
                            YOUR TRUSTED B2B WHOLESALE PARTNER
                        </span>
                    </div>
                </a>
                <p class="footer-about">Elevate your mobile experience with our curated collection of premium accessories.</p>
            </div>
            <div class="footer-links-col">
                <h4>Links</h4>
                <ul>
                    <li><a href="{{ route('products.index') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-links-col">
                <h4>Products</h4>
                <ul>
                    @foreach($footerCategories as $cat)
                        <li><a href="{{ route('products.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-links-col">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }}. Apna Mobile Hub Online Powered By <a href="https://ottomern.com" target="_blank" style="color: var(--accent-orange); font-weight: 700; text-decoration: none;">Ottomern Technologies</a></p>
        </div>
    </footer>

    <!-- Initialize Global Scroll Animations -->
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            if ('IntersectionObserver' in window) {
                const revealObserver = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('active');
                            revealObserver.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.05,
                    rootMargin: '0px 0px -40px 0px'
                });

                document.querySelectorAll('.reveal-fade-in, .reveal-slide-up, .reveal-slide-left, .reveal-slide-right, .reveal-scale, .reveal-diagonal-slide').forEach((el) => {
                    revealObserver.observe(el);
                });
            } else {
                document.querySelectorAll('.reveal-fade-in, .reveal-slide-up, .reveal-slide-left, .reveal-slide-right, .reveal-scale, .reveal-diagonal-slide').forEach((el) => {
                    el.classList.add('active');
                });
            }
            // Navbar scroll listener
            const navbar = document.querySelector('.navbar-container');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        navbar.classList.add('navbar-scrolled');
                    } else {
                        navbar.classList.remove('navbar-scrolled');
                    }
                }, { passive: true });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
