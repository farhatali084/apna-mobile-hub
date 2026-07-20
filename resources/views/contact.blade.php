@extends('layouts.app')

@section('title', 'Contact Us - Apna Mobile Hub | Wholesale Enquiry & Support | Jamshedpur')

@section('meta_description', 'Contact Apna Mobile Hub for wholesale mobile accessories enquiries, bulk orders & support. WhatsApp: 7979747352, Email: Apnamobilehubjsr@gmail.com. Visit us at Shop No. 456, Sanjay Market, Sakchi, Jamshedpur.')
@section('meta_keywords', 'contact Apna Mobile Hub, wholesale enquiry mobile accessories, bulk order mobile cases, Jamshedpur mobile shop, Sakchi market contact, WhatsApp order accessories')
@section('og_title', 'Contact Us - Apna Mobile Hub | Get In Touch')
@section('og_description', 'Reach out to Apna Mobile Hub for wholesale mobile accessories enquiries, bulk orders & support. WhatsApp, Phone, Email or visit our store in Sakchi, Jamshedpur.')

@section('content')
@php
    $contactTitle = \App\Models\Setting::getValue('contact_title', 'Contact Us');
    $contactSubtitle = \App\Models\Setting::getValue('contact_subtitle', "Have questions about product availability or custom orders? Reach out to us...");
    $contactWhatsapp = \App\Models\Setting::getValue('contact_whatsapp', '917979747352');
    $contactPhone = \App\Models\Setting::getValue('contact_phone', '+91 79797 47352');
    $contactHours = \App\Models\Setting::getValue('contact_hours', '10:00 AM - 8:00 PM');
    $contactEmail = \App\Models\Setting::getValue('contact_email', 'Apnamobilehubjsr@gmail.com');
    $contactAddress = \App\Models\Setting::getValue('contact_address', 'Shop No. 456, Sanjay Market, Sakchi, Jamshedpur');
@endphp

<div class="container" style="padding-top: 40px; padding-bottom: 80px;">
    <!-- 1. Page Header -->
    <header class="page-header-section reveal-slide-up" style="text-align: center; margin-bottom: 60px;">
        <span class="cat-badge badge-orange" style="margin-bottom: 10px;">Get In Touch</span>
        <h1 style="font-size: 48px; font-weight: 900; line-height: 1.1; font-family: 'Montserrat', sans-serif; color: var(--text-primary); text-transform: uppercase;">
            {!! preg_replace('/(Us)$/i', '<span class="orange-text">$1</span>', e($contactTitle)) !!}
        </h1>
        <p style="color: var(--text-secondary); max-width: 600px; margin: 15px auto 0; font-size: 15px; line-height: 1.6;">
            {{ $contactSubtitle }}
        </p>
    </header>

    <!-- 2. Contact Split Grid -->
    <div class="contact-split-grid" style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 50px; align-items: start;">
        <!-- Left: Quick Info Box -->
        <div class="reveal-slide-left" style="display: flex; flex-direction: column; gap: 30px;">
            <div class="contact-info-box" style="background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 20px; padding: 40px; box-shadow: var(--shadow-md); display: flex; flex-direction: column; gap: 24px;">
                <h3 style="font-size: 22px; font-weight: 850; font-family: 'Montserrat', sans-serif; color: var(--text-primary); border-bottom: 1px dashed var(--border-color); padding-bottom: 15px; margin: 0;">
                    Support Channels
                </h3>

                <!-- WhatsApp Channel -->
                <div style="display: flex; gap: 16px; align-items: flex-start;">
                    <div style="width: 40px; height: 40px; background-color: rgba(37, 211, 102, 0.1); border-radius: 10px; color: var(--whatsapp-color); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fa-solid fa-circle"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px;">WhatsApp Chat</h4>
                        <p style="font-size: 12px; color: var(--text-secondary); line-height: 1.5; margin-bottom: 8px;">
                            Chat with us for queries, order status, or help.
                        </p>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactWhatsapp) }}" target="_blank" style="font-size: 12px; font-weight: 800; color: var(--whatsapp-color); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                            Start Chatting <i class="fa-solid fa-circle" style="width: 12px; height: 12px;"></i>
                        </a>
                    </div>
                </div>

                <!-- Call Support -->
                <div style="display: flex; gap: 16px; align-items: flex-start;">
                    <div style="width: 40px; height: 40px; background-color: rgba(255, 94, 43, 0.1); border-radius: 10px; color: var(--accent-orange); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px;">Phone Support</h4>
                        <p style="font-size: 12px; color: var(--text-secondary); line-height: 1.5; margin: 0;">
                            {{ $contactPhone }} ({{ $contactHours }})
                        </p>
                    </div>
                </div>

                <!-- Email Channel -->
                <div style="display: flex; gap: 16px; align-items: flex-start;">
                    <div style="width: 40px; height: 40px; background-color: rgba(99, 102, 241, 0.1); border-radius: 10px; color: #6366f1; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px;">Email Support</h4>
                        <p style="font-size: 12px; color: var(--text-secondary); line-height: 1.5; margin: 0;">
                            {{ $contactEmail }}
                        </p>
                    </div>
                </div>

                <!-- Store Location -->
                <div style="display: flex; gap: 16px; align-items: flex-start;">
                    <div style="width: 40px; height: 40px; background-color: rgba(245, 158, 11, 0.1); border-radius: 10px; color: var(--accent-gold); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fa-solid fa-circle"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px;">Store Location</h4>
                        <p style="font-size: 12px; color: var(--text-secondary); line-height: 1.5; margin: 0;">
                            {{ $contactAddress }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Message Form -->
        <div class="reveal-slide-right contact-form-card" style="background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 20px; padding: 40px; box-shadow: var(--shadow-md);">
            <h3 style="font-size: 22px; font-weight: 850; font-family: 'Montserrat', sans-serif; color: var(--text-primary); border-bottom: 1px dashed var(--border-color); padding-bottom: 15px; margin-bottom: 24px;">
                Send Us A Message
            </h3>

            <form action="#" onsubmit="event.preventDefault(); alert('Thank you for reaching out! We will contact you soon.'); this.reset();" style="display: flex; flex-direction: column; gap: 16px;">
                <div class="contact-form-name-email-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" required class="form-control" placeholder="John Doe">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" required class="form-control" placeholder="john@example.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" required class="form-control" placeholder="Product Query / Feedback">
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" rows="5" required class="form-control" placeholder="Describe your inquiry..."></textarea>
                </div>

                <button type="submit" class="btn-orange" style="width: 100%; border: none; cursor: pointer; justify-content: center; font-weight: 800; text-transform: uppercase;">
                    Submit Inquiry
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
