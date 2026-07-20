<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->timestamps();
        });

        // 2. Create filter_groups table
        Schema::create('filter_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->index();
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // 3. Create filter_values table
        Schema::create('filter_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filter_group_id')->constrained('filter_groups')->cascadeOnDelete();
            $table->string('value');
            $table->string('color_hex')->nullable();
            $table->timestamps();

            $table->unique(['filter_group_id', 'value']);
        });

        // 4. Create product_filter_value pivot table
        Schema::create('product_filter_value', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('filter_value_id')->constrained('filter_values')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['product_id', 'filter_value_id']);
        });

        // 5. Add category_id to products table
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->index()->constrained('categories')->restrictOnDelete();
        });

        // 6. Migrate existing string categories to categories table & update products
        $uniqueCategories = DB::table('products')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        foreach ($uniqueCategories as $catName) {
            if (empty(trim($catName))) continue;
            
            $slug = Str::slug($catName);
            // Ensure unique slug
            $existing = DB::table('categories')->where('slug', $slug)->first();
            if ($existing) {
                $slug .= '-' . rand(10, 99);
            }

            $categoryId = DB::table('categories')->insertGetId([
                'name' => $catName,
                'slug' => $slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('products')
                ->where('category', $catName)
                ->update(['category_id' => $categoryId]);
        }

        // 7. Drop old category column from products
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back category string column to products
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->nullable();
        });

        // Restore category strings
        $products = DB::table('products')
            ->whereNotNull('category_id')
            ->get();

        foreach ($products as $prod) {
            $cat = DB::table('categories')->where('id', $prod->category_id)->first();
            if ($cat) {
                DB::table('products')
                    ->where('id', $prod->id)
                    ->update(['category' => $cat->name]);
            }
        }

        // Drop foreign keys and tables
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('product_filter_value');
        Schema::dropIfExists('filter_values');
        Schema::dropIfExists('filter_groups');
        Schema::dropIfExists('categories');
    }
};
