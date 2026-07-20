<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Apna Mobile Hub - Premium Protection & Gear')</title>
    
    <!-- Google Fonts: Montserrat & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Montserrat:wght@400;500;700;800;900&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons (Lucide Icons via CDN) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
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
                <span style="display: flex; align-items: center; gap: 6px;"><i data-lucide="building-2" style="width: 14px; height: 14px; color: var(--accent-orange);"></i> B2B WHOLESALE SPECIALIST</span>
                <span style="display: flex; align-items: center; gap: 6px;"><i data-lucide="badge-check" style="width: 14px; height: 14px; color: var(--accent-orange);"></i> Best Prices</span>
                <span style="display: flex; align-items: center; gap: 6px;"><i data-lucide="shield-check" style="width: 14px; height: 14px; color: var(--accent-orange);"></i> Trusted Quality</span>
                <span style="display: flex; align-items: center; gap: 6px;"><i data-lucide="truck" style="width: 14px; height: 14px; color: var(--accent-orange);"></i> Reliable Supply</span>
            </div>
            <div class="top-bar-right">
                <a href="tel:+917979747352" style="color: #F8FAFC; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                    <i data-lucide="phone" style="width: 13px; height: 13px; color: var(--accent-orange);"></i> Bulk Enquiry: +91 79797 47352
                </a>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <header class="navbar-container" style="position: sticky; top: 0; z-index: 1000; background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
        <div class="navbar-content container" style="height: 86px; display: flex; justify-content: space-between; align-items: center; gap: 20px;">
            
            <!-- Mobile Menu Toggle Button -->
            <button @click="mobileMenuOpen = true" class="mobile-menu-toggle" style="background: none; border: none; color: #0F172A; cursor: pointer; display: none; align-items: center; justify-content: center; padding: 8px; margin-left: -8px;">
                <i data-lucide="menu" style="width: 24px; height: 24px;"></i>
            </button>

            <!-- Brand Logo -->
            <a href="{{ route('products.index') }}" class="logo-link" style="display: flex; align-items: center; gap: 12px; text-decoration: none; white-space: nowrap;">
                <img src="/images/logo.png" alt="Apna Mobile Hub Logo" style="height: 48px; width: auto; object-fit: contain; border-radius: 8px;">
                <div style="display: flex; flex-direction: column; line-height: 1.1;">
                    <span style="font-size: 18px; font-weight: 900; color: #0F172A; font-family: 'Montserrat', sans-serif; letter-spacing: -0.5px; text-transform: uppercase;">
                        Apna Mobile <span style="color: var(--accent-orange);">Hub</span>
                    </span>
                    <span style="font-size: 8px; font-weight: 800; color: #64748B; letter-spacing: 0.5px; text-transform: uppercase; margin-top: 2px;" class="logo-subtitle-hide">
                        YOUR TRUSTED B2B WHOLESALE PARTNER
                    </span>
                </div>
            </a>
            
            <!-- Navbar Nav Links -->
            <nav class="navbar-nav" style="display: flex; align-items: center; gap: 16px;">
                <a href="{{ route('products.index') }}" class="nav-btn {{ request()->routeIs('products.index') && !request('search') && !request('category') ? 'active' : '' }}">Home</a>
                <div class="nav-dropdown-wrapper" style="position: relative; display: inline-block;">
                    <a href="{{ route('products.index') }}#categories" class="nav-btn" style="display: flex; align-items: center; gap: 4px; text-transform: capitalize;">
                        Categories <i data-lucide="chevron-down" style="width: 12px; height: 12px;"></i>
                    </a>
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
                            <i data-lucide="search" style="width: 14px; height: 14px;"></i>
                        </button>
                    </div>
                </form>

                <!-- Cart Button -->
                <a href="{{ route('cart.index') }}" class="nav-icon-link" title="Shopping Cart" style="color: #0F172A; display: flex; align-items: center; position: relative; text-decoration: none;">
                    <i data-lucide="shopping-cart" class="nav-icon" style="width: 20px; height: 20px;"></i>
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
                        <i data-lucide="search" style="width: 14px; height: 14px;"></i>
                    </button>
                </div>
            </form>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 9999; display: none;" 
         :style="mobileMenuOpen ? 'display: block !important' : 'display: none !important'"
         @click="mobileMenuOpen = false">
        
        <!-- Drawer Panel -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             style="width: 280px; max-width: 80%; height: 100%; background-color: #ffffff; padding: 24px; display: flex; flex-direction: column; gap: 20px; box-shadow: 5px 0 25px rgba(0,0,0,0.15);"
             @click.stop>
            
            <!-- Drawer Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
                <span style="font-size: 16px; font-weight: 900; color: #0F172A; text-transform: uppercase; font-family: 'Montserrat', sans-serif;">Navigation</span>
                <button @click="mobileMenuOpen = false" style="background: none; border: none; cursor: pointer; color: #64748B;">
                    <i data-lucide="x" style="width: 20px; height: 20px;"></i>
                </button>
            </div>

            <!-- Drawer Links -->
            <div style="display: flex; flex-direction: column; gap: 14px; font-family: 'Outfit', sans-serif;">
                <a href="{{ route('products.index') }}" @click="mobileMenuOpen = false" style="font-size: 15px; font-weight: 700; color: #0F172A; text-decoration: none; padding: 8px 0; display: block; border-bottom: 1px solid #F1F5F9;">Home</a>
                <a href="{{ route('products.index') }}#categories" @click="mobileMenuOpen = false" style="font-size: 15px; font-weight: 700; color: #0F172A; text-decoration: none; padding: 8px 0; display: block; border-bottom: 1px solid #F1F5F9;">Categories</a>
                <a href="{{ route('products.index') }}#brands" @click="mobileMenuOpen = false" style="font-size: 15px; font-weight: 700; color: #0F172A; text-decoration: none; padding: 8px 0; display: block; border-bottom: 1px solid #F1F5F9;">Brands</a>
                <a href="{{ route('products.index') }}#deals" @click="mobileMenuOpen = false" style="font-size: 15px; font-weight: 700; color: #0F172A; text-decoration: none; padding: 8px 0; display: block; border-bottom: 1px solid #F1F5F9;">Deals</a>
                <a href="{{ route('products.index') }}#bulk-order" @click="mobileMenuOpen = false" style="font-size: 15px; font-weight: 700; color: #0F172A; text-decoration: none; padding: 8px 0; display: block; border-bottom: 1px solid #F1F5F9;">Bulk Order</a>
                <a href="{{ route('about') }}" @click="mobileMenuOpen = false" style="font-size: 15px; font-weight: 700; color: #0F172A; text-decoration: none; padding: 8px 0; display: block; border-bottom: 1px solid #F1F5F9;">About Us</a>
                <a href="{{ route('contact') }}" @click="mobileMenuOpen = false" style="font-size: 15px; font-weight: 700; color: #0F172A; text-decoration: none; padding: 8px 0; display: block;">Contact</a>
            </div>

            <!-- Drawer Utility -->
            <div style="margin-top: auto; padding-top: 16px; border-top: 1px solid var(--border-color); font-size: 12px; font-weight: 600; color: #64748B;">
                <a href="tel:+917979747352" style="color: #0F172A; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="phone" style="width: 14px; height: 14px; color: var(--accent-orange);"></i> B2B Helpline: +91 79797 47352
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
            <p>&copy; {{ date('Y') }}. Apna Mobile Hub Online Powered By Ecommerce</p>
        </div>
    </footer>

    <!-- Initialize Lucide Icons & Global Scroll Animations -->
    <script>
        lucide.createIcons();

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
