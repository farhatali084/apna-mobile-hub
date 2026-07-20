<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default admin user
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        // Seed default settings
        \App\Models\Setting::updateOrCreate(
            ['key' => 'shipping_fee'],
            [
                'label' => 'Shipping Fee',
                'value' => '0',
                'type' => 'number',
            ]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'free_shipping_threshold'],
            [
                'label' => 'Free Shipping Threshold (Set 0 to disable)',
                'value' => '0',
                'type' => 'number',
            ]
        );

        // Seed default About Us page settings
        \App\Models\Setting::updateOrCreate(
            ['key' => 'about_store_image'],
            [
                'label' => 'About Us - Store Image Path',
                'value' => 'images/about_store.png',
                'type' => 'image',
            ]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'about_title'],
            [
                'label' => 'About Us - Page Title',
                'value' => 'About Us',
                'type' => 'text',
            ]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'about_story_heading'],
            [
                'label' => 'About Us - Story Heading',
                'value' => 'Empowering Your Digital Lifestyle',
                'type' => 'text',
            ]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'about_story_description_1'],
            [
                'label' => 'About Us - Story Description 1',
                'value' => 'Founded with a simple mission in mind, Apna Mobile Hub has grown to become a leading e-commerce store dedicated to premium-quality mobile protection, charging systems, and smart accessories. We believe that your mobile devices deserve nothing less than the best.',
                'type' => 'textarea',
            ]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'about_story_description_2'],
            [
                'label' => 'About Us - Story Description 2',
                'value' => 'Every case we offer, every tempered glass we stock, and every gadget we present is carefully inspected. We utilize advanced optimization technologies (including native WebP image processing) to ensure that your experience—both on our platform and in hand—is fast, efficient, and reliable.',
                'type' => 'textarea',
            ]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'about_happy_customers'],
            [
                'label' => 'About Us - Happy Customers Count',
                'value' => '5000+',
                'type' => 'text',
            ]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'about_quality_assured'],
            [
                'label' => 'About Us - Quality Assured Percentage',
                'value' => '100%',
                'type' => 'text',
            ]
        );

        // Seed default Contact Us page settings
        \App\Models\Setting::updateOrCreate(
            ['key' => 'contact_title'],
            [
                'label' => 'Contact Us - Page Title',
                'value' => 'Contact Us',
                'type' => 'text',
            ]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'contact_subtitle'],
            [
                'label' => 'Contact Us - Page Subtitle',
                'value' => "Have questions about product availability or custom orders? Reach out to us and we'll reply as quickly as possible.",
                'type' => 'textarea',
            ]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'contact_whatsapp'],
            [
                'label' => 'Contact Us - WhatsApp Phone Number (Digits Only)',
                'value' => '917979747352',
                'type' => 'text',
            ]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'contact_phone'],
            [
                'label' => 'Contact Us - Phone Support Number',
                'value' => '+91 79797 47352',
                'type' => 'text',
            ]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'contact_hours'],
            [
                'label' => 'Contact Us - Phone Support Hours',
                'value' => '10:00 AM - 8:00 PM',
                'type' => 'text',
            ]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'contact_email'],
            [
                'label' => 'Contact Us - Email Support Address',
                'value' => 'Apnamobilehubjsr@gmail.com',
                'type' => 'text',
            ]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'contact_address'],
            [
                'label' => 'Contact Us - Store Location Address',
                'value' => 'Shop No. 456, Sanjay Market, Sakchi, Jsr',
                'type' => 'textarea',
            ]
        );

        // Seed default reviews
        \App\Models\Review::updateOrCreate(
            ['customer_name' => 'Brad Wilson'],
            [
                'customer_designation' => 'Verified Buyer',
                'review_text' => 'Great quality tempered glass! Fits my phone perfectly and the screen feels extremely smooth.',
                'rating' => 5,
                'display_order' => 1,
            ]
        );

        \App\Models\Review::updateOrCreate(
            ['customer_name' => 'Sarah Jenkins'],
            [
                'customer_designation' => 'Verified Buyer',
                'review_text' => 'Ordered AirPods cases, standard delivery took only 2 days. The case feels solid and premium!',
                'rating' => 5,
                'display_order' => 2,
            ]
        );

        \App\Models\Review::updateOrCreate(
            ['customer_name' => 'Amit Sharma'],
            [
                'customer_designation' => 'Verified Buyer',
                'review_text' => 'Bahut badhiya product aur services hain. WhatsApp par support fast mila. Highly recommended!',
                'rating' => 5,
                'display_order' => 3,
            ]
        );

        $this->call([
            ProductSeeder::class,
            SliderSeeder::class,
        ]);

        if (!app()->environment('production')) {
            $this->call([
                OrderSeeder::class,
            ]);
        }
    }
}
