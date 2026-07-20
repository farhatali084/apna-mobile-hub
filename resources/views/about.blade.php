@extends('layouts.app')

@section('title', 'About Us - Apna Mobile Hub | Trusted B2B Wholesale Mobile Accessories Partner')

@section('meta_description', 'Learn about Apna Mobile Hub - Jamshedpur\'s trusted B2B wholesale mobile accessories store. 5000+ happy customers, 100% quality assured. Discover our story, values & commitment to premium protection gear.')
@section('meta_keywords', 'about Apna Mobile Hub, mobile accessories store Jamshedpur, B2B wholesale accessories, trusted mobile accessories partner, Sakchi market Jamshedpur')
@section('og_title', 'About Us - Apna Mobile Hub | Our Story & Mission')
@section('og_description', 'Discover the passion behind Apna Mobile Hub and our commitment to bringing you the finest premium mobile accessories at wholesale prices.')

@section('content')
@php
    $aboutTitle = \App\Models\Setting::getValue('about_title', 'About Us');
    $aboutStoryHeading = \App\Models\Setting::getValue('about_story_heading', 'Empowering Your Digital Lifestyle');
    $aboutDesc1 = \App\Models\Setting::getValue('about_story_description_1', 'Founded with a simple mission...');
    $aboutDesc2 = \App\Models\Setting::getValue('about_story_description_2', 'Every case we offer...');
    $aboutHappy = \App\Models\Setting::getValue('about_happy_customers', '5000+');
    $aboutQuality = \App\Models\Setting::getValue('about_quality_assured', '100%');
    
    $storeImageSetting = \App\Models\Setting::getValue('about_store_image', 'images/about_store.png');
    if ($storeImageSetting) {
        if (str_starts_with($storeImageSetting, 'http') || str_starts_with($storeImageSetting, 'images/')) {
            $storeImageUrl = asset($storeImageSetting);
        } else {
            $storeImageUrl = asset('storage/' . $storeImageSetting);
        }
    } else {
        $storeImageUrl = asset('images/about_store.png');
    }
@endphp

<div class="container" style="padding-top: 40px; padding-bottom: 80px;">
    <!-- 1. Page Header -->
    <header class="page-header-section reveal-slide-up" style="text-align: center; margin-bottom: 60px;">
        <span class="cat-badge badge-orange" style="margin-bottom: 10px;">Our Story</span>
        <h1 style="font-size: 48px; font-weight: 900; line-height: 1.1; font-family: 'Montserrat', sans-serif; color: var(--text-primary); text-transform: uppercase;">
            {!! preg_replace('/(Us)$/i', '<span class="orange-text">$1</span>', e($aboutTitle)) !!}
        </h1>
        <p style="color: var(--text-secondary); max-width: 600px; margin: 15px auto 0; font-size: 15px; line-height: 1.6;">
            Discover the passion behind Apna Mobile Hub and our commitment to bringing you the finest premium mobile accessories.
        </p>
    </header>

    <!-- 2. Main Narrative Section (Split Grid) -->
    <div class="about-story-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; margin-bottom: 80px;">
        <!-- Left: Store Photo Box -->
        <div class="reveal-slide-left" style="background-color: var(--bg-surface); border: 1px solid var(--border-color); padding: 15px; border-radius: 24px; box-shadow: var(--shadow-md); position: relative; overflow: hidden; display: flex; justify-content: center; align-items: center; min-height: 380px;">
            <img src="{{ $storeImageUrl }}" alt="Apna Mobile Hub Store" style="width: 100%; height: 350px; object-fit: cover; border-radius: 16px; filter: drop-shadow(0 15px 30px rgba(0,0,0,0.08));">
        </div>

        <!-- Right: Story Details -->
        <div class="reveal-slide-right" style="display: flex; flex-direction: column; gap: 20px;">
            <h2 style="font-size: 32px; font-weight: 900; font-family: 'Montserrat', sans-serif; color: var(--text-primary);">
                {{ $aboutStoryHeading }}
            </h2>
            <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.7;">
                {!! nl2br(e($aboutDesc1)) !!}
            </p>
            <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.7;">
                {!! nl2br(e($aboutDesc2)) !!}
            </p>
            <div style="display: flex; gap: 20px; margin-top: 10px;">
                <div style="flex: 1; background-color: var(--bg-surface); border: 1px solid var(--border-color); padding: 15px; border-radius: 12px;">
                    <h4 style="font-weight: 800; color: var(--accent-orange); font-size: 18px; margin-bottom: 4px;">{{ $aboutHappy }}</h4>
                    <span style="font-size: 12px; color: var(--text-secondary); font-weight: 600;">Happy Customers</span>
                </div>
                <div style="flex: 1; background-color: var(--bg-surface); border: 1px solid var(--border-color); padding: 15px; border-radius: 12px;">
                    <h4 style="font-weight: 800; color: var(--accent-orange); font-size: 18px; margin-bottom: 4px;">{{ $aboutQuality }}</h4>
                    <span style="font-size: 12px; color: var(--text-secondary); font-weight: 600;">Quality Assured</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Our Values Pillars (Three Column Grid) -->
    <section>
        <h3 class="reveal-slide-up" style="font-size: 28px; font-weight: 900; font-family: 'Montserrat', sans-serif; color: var(--text-primary); text-align: center; margin-bottom: 40px; text-transform: uppercase;">
            Our Core <span class="orange-text">Pillars</span>
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
            <!-- Pillar 1 -->
            <div class="reveal-diagonal-slide stagger-1 pillar-card-rz">
                <div class="pillar-icon-box">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h4>Uncompromised Protection</h4>
                <p>
                    We carefully curate military-grade protection cases, camera guards, and drop-tested covers to safeguard your device against daily hazards.
                </p>
            </div>

            <!-- Pillar 2 -->
            <div class="reveal-diagonal-slide stagger-2 pillar-card-rz">
                <div class="pillar-icon-box">
                    <i class="fa-solid fa-circle"></i>
                </div>
                <h4>Speed & Efficiency</h4>
                <p>
                    From dynamic image compressors that make browsing extremely fast, to quick dispatch services, we value your time and convenience.
                </p>
            </div>

            <!-- Pillar 3 -->
            <div class="reveal-diagonal-slide stagger-3 pillar-card-rz">
                <div class="pillar-icon-box">
                    <i class="fa-solid fa-circle"></i>
                </div>
                <h4>Real Customer Support</h4>
                <p>
                    No auto-bots, no infinite waiting lists. Connect directly with our team on WhatsApp for personalized advice, order details, and support.
                </p>
            </div>
        </div>
    </section>
</div>
@endsection
