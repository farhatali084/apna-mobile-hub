<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\FilterGroup;
use App\Models\FilterValue;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to truncate safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_filter_value')->truncate();
        DB::table('category_filter_group')->truncate();
        DB::table('category_filter_value')->truncate();
        ProductImage::truncate();
        FilterValue::truncate();
        FilterGroup::truncate();
        Product::truncate();
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create categories
        $chargersCat = Category::create(['name' => 'Chargers', 'slug' => 'chargers']);
        $cablesCat = Category::create(['name' => 'Cables', 'slug' => 'cables']);
        $earphonesCat = Category::create(['name' => 'Earphones', 'slug' => 'earphones']);
        $powerBanksCat = Category::create(['name' => 'Power Banks', 'slug' => 'power-banks']);
        $phoneCasesCat = Category::create(['name' => 'Phone Cases', 'slug' => 'phone-cases']);
        $screenProtectorsCat = Category::create(['name' => 'Screen Protectors', 'slug' => 'screen-protectors']);
        $carAccessoriesCat = Category::create(['name' => 'Car Accessories', 'slug' => 'car-accessories']);
        $smartWatchesCat = Category::create(['name' => 'Smart Watches', 'slug' => 'smart-watches']);

        // 2. Create filter groups & values globally
        $colorsGroup = FilterGroup::create([
            'name' => 'Colors',
            'slug' => 'colors',
            'display_order' => 1,
        ]);

        $phoneCasesCat->filterGroups()->attach($colorsGroup->id);

        $blackVal = FilterValue::create([
            'filter_group_id' => $colorsGroup->id,
            'value' => 'Active black',
            'color_hex' => '#000000',
        ]);

        $purpleVal = FilterValue::create([
            'filter_group_id' => $colorsGroup->id,
            'value' => 'Aurora purple',
            'color_hex' => '#7E57C2',
        ]);

        $blueVal = FilterValue::create([
            'filter_group_id' => $colorsGroup->id,
            'value' => 'Blue',
            'color_hex' => '#2196F3',
        ]);

        $phoneCasesCat->filterValues()->attach([$blackVal->id, $purpleVal->id, $blueVal->id]);

        // 3. Create products with gallery images
        $seedGallery = function (Product $product, array $imagePaths) {
            foreach ($imagePaths as $i => $path) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'sort_order' => $i,
                ]);
            }
        };

        // Chargers
        $p1 = Product::create([
            'name' => 'AMH 65W GaN Charger',
            'slug' => 'amh-65w-gan-charger',
            'description' => 'Fast charging compact adapter with dual USB-C ports for phones and laptops.',
            'price' => 1299.00,
            'image_path' => '/images/cat_chargers.png',
            'category_id' => $chargersCat->id,
            'stock' => 500,
            'is_featured' => true,
            'rating' => 4.9,
            'rating_count' => 142,
        ]);
        $seedGallery($p1, ['/images/cat_cables.png', '/images/cat_power_banks.png', '/images/hero_accessories.png']);

        // Cables
        $p2 = Product::create([
            'name' => 'Type-C to C Cable (100W)',
            'slug' => 'type-c-to-c-cable-100w',
            'description' => 'Durable braided charging cable supporting up to 100W power delivery.',
            'price' => 299.00,
            'image_path' => '/images/cat_cables.png',
            'category_id' => $cablesCat->id,
            'stock' => 1000,
            'is_featured' => true,
            'rating' => 4.8,
            'rating_count' => 96,
        ]);
        $seedGallery($p2, ['/images/cat_chargers.png', '/images/cat_power_banks.png']);

        // Earphones
        $p3 = Product::create([
            'name' => 'AMH TWS Earbuds Pro',
            'slug' => 'amh-tws-earbuds-pro',
            'description' => 'Premium true wireless stereo earbuds with active noise cancellation.',
            'price' => 1099.00,
            'image_path' => '/images/cat_earphones.png',
            'category_id' => $earphonesCat->id,
            'stock' => 400,
            'is_featured' => true,
            'rating' => 4.9,
            'rating_count' => 210,
        ]);
        $seedGallery($p3, ['/images/category_airpods.png', '/images/category_headphones.png', '/images/product_airpods_case.png']);

        // Power Banks
        $p4 = Product::create([
            'name' => '10000mAh Power Bank',
            'slug' => '10000mah-power-bank',
            'description' => 'Sleek high-density portable backup battery with fast charging ports.',
            'price' => 899.00,
            'image_path' => '/images/cat_power_banks.png',
            'category_id' => $powerBanksCat->id,
            'stock' => 350,
            'is_featured' => true,
            'rating' => 4.7,
            'rating_count' => 84,
        ]);
        $seedGallery($p4, ['/images/cat_chargers.png', '/images/cat_cables.png']);

        // Phone Cases
        $caseProduct = Product::create([
            'name' => 'Premium Rugged Case',
            'slug' => 'premium-rugged-case',
            'description' => 'Shockproof hybrid bumper protective case designed for ultimate drops.',
            'price' => 1299.00,
            'image_path' => '/images/product_iphone_case.png',
            'category_id' => $phoneCasesCat->id,
            'stock' => 600,
            'is_featured' => true,
            'rating' => 4.9,
            'rating_count' => 320,
        ]);
        $caseProduct->filterValues()->sync([$blackVal->id, $blueVal->id]);
        $seedGallery($caseProduct, ['/images/cat_screen_protectors.png', '/images/category_iphones.png', '/images/product_airpods_case.png']);

        // Screen Protectors
        $p6 = Product::create([
            'name' => 'Tempered Glass Guard',
            'slug' => 'tempered-glass-guard',
            'description' => 'High clarity screen guard protector with 9H hardness level.',
            'price' => 199.00,
            'image_path' => '/images/cat_screen_protectors.png',
            'category_id' => $screenProtectorsCat->id,
            'stock' => 1500,
            'is_featured' => true,
            'rating' => 4.8,
            'rating_count' => 540,
        ]);
        $seedGallery($p6, ['/images/product_iphone_case.png', '/images/category_iphones.png']);

        // Car Accessories
        $p7 = Product::create([
            'name' => 'Car Charger (Dual PD)',
            'slug' => 'car-charger-dual-pd',
            'description' => 'Miniature high speed car charging adapter with dual USB-C output.',
            'price' => 399.00,
            'image_path' => '/images/cat_car_accessories.png',
            'category_id' => $carAccessoriesCat->id,
            'stock' => 300,
            'is_featured' => true,
            'rating' => 4.6,
            'rating_count' => 58,
        ]);
        $seedGallery($p7, ['/images/cat_chargers.png', '/images/cat_cables.png', '/images/hero_accessories.png']);

        // Smart Watches
        $p8 = Product::create([
            'name' => 'AMH Sport Smartwatch',
            'slug' => 'amh-sport-smartwatch',
            'description' => 'Stylish sports smart watch tracking heart rate, steps, and sleep.',
            'price' => 1499.00,
            'image_path' => '/images/product_watch.png',
            'category_id' => $smartWatchesCat->id,
            'stock' => 200,
            'is_featured' => true,
            'rating' => 4.9,
            'rating_count' => 180,
        ]);
        $seedGallery($p8, ['/images/category_watch.png', '/images/hero_accessories.png', '/images/cat_power_banks.png']);
    }
}
