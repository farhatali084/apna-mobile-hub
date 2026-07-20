<?php

namespace Database\Seeders;

use App\Models\PageSeo;
use Illuminate\Database\Seeder;

class PageSeoSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'page_identifier' => 'home',
                'page_name' => 'Home Page',
                'meta_title' => 'Apna Mobile Hub - Wholesale Mobile Accessories | Chargers, Cables, Cases | Jamshedpur',
                'meta_description' => 'Buy wholesale mobile accessories at factory-direct prices from Apna Mobile Hub, Jamshedpur. Premium GaN chargers, USB-C cables, phone cases, earphones, power banks & smart watches. GST billing, pan India delivery & bulk pricing.',
                'meta_keywords' => 'wholesale mobile accessories, B2B mobile accessories India, bulk phone cases, GaN chargers wholesale, USB cables bulk, TWS earbuds wholesale, power banks bulk, smart watches wholesale, Jamshedpur mobile accessories, Sakchi market, Apna Mobile Hub',
                'og_title' => 'Apna Mobile Hub - Wholesale Mobile Accessories For Businesses',
                'og_description' => 'India\'s trusted B2B wholesale partner for premium mobile cases, GaN fast chargers, cables & accessories at factory-direct prices. GST Billing | Pan India Delivery | Bulk Pricing.',
                'robots' => 'index, follow',
                'is_active' => true,
            ],
            [
                'page_identifier' => 'about',
                'page_name' => 'About Us',
                'meta_title' => 'About Apna Mobile Hub - Your Trusted B2B Wholesale Partner | Jamshedpur',
                'meta_description' => 'Learn about Apna Mobile Hub - Jamshedpur\'s trusted B2B wholesale partner for premium mobile accessories. Shop No. 456, Sanjay Market, Sakchi. GST billing, pan India delivery & dedicated support.',
                'meta_keywords' => 'about Apna Mobile Hub, mobile accessories wholesale Jamshedpur, B2B wholesale partner, Sakchi market, Sanjay Market',
                'og_title' => 'About Apna Mobile Hub - Your Trusted B2B Wholesale Partner',
                'og_description' => 'Jamshedpur\'s trusted B2B wholesale partner for premium mobile accessories since establishment.',
                'robots' => 'index, follow',
                'is_active' => true,
            ],
            [
                'page_identifier' => 'contact',
                'page_name' => 'Contact Us',
                'meta_title' => 'Contact Apna Mobile Hub - B2B Wholesale Enquiry | +91 79797 47352',
                'meta_description' => 'Contact Apna Mobile Hub for wholesale mobile accessories enquiry. Call +91 79797 47352 or visit Shop No. 456, Sanjay Market, Sakchi, Jamshedpur. Email: Apnamobilehubjsr@gmail.com',
                'meta_keywords' => 'contact Apna Mobile Hub, wholesale enquiry, bulk order mobile accessories, Jamshedpur contact, B2B enquiry',
                'og_title' => 'Contact Apna Mobile Hub - Wholesale Enquiry',
                'og_description' => 'Get in touch for wholesale mobile accessories. Call +91 79797 47352 or visit our store in Sakchi, Jamshedpur.',
                'robots' => 'index, follow',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $page) {
            PageSeo::updateOrCreate(
                ['page_identifier' => $page['page_identifier']],
                $page
            );
        }
    }
}
