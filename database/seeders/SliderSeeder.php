<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slider;
use Illuminate\Support\Facades\DB;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Slider::truncate();

        Slider::create([
            'title' => "Wholesale Mobile Accessories For Businesses",
            'subtitle' => "India's trusted B2B partner for premium cases, GaN fast chargers, and durable cables at certified factory-direct prices.",
            'button_text' => "Shop Now",
            'button_link' => "#deals",
            'image_path' => "/images/hero_accessories.png", // A composite image of accessories
            'is_active' => true,
            'display_order' => 1,
        ]);

        Slider::create([
            'title' => "Premium GaN Fast Chargers",
            'subtitle' => "High-speed charging solutions with dual ports. Stock up for your retail store today.",
            'button_text' => "View Chargers",
            'button_link' => "/products?category=chargers",
            'image_path' => "/images/cat_chargers.png",
            'is_active' => true,
            'display_order' => 2,
        ]);
        
        Slider::create([
            'title' => "Durable Braided Cables",
            'subtitle' => "100W power delivery cables built to last. Best margins guaranteed for wholesalers.",
            'button_text' => "View Cables",
            'button_link' => "/products?category=cables",
            'image_path' => "/images/cat_cables.png",
            'is_active' => true,
            'display_order' => 3,
        ]);
    }
}
