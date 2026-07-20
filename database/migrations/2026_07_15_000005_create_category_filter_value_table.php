<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_filter_value', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('filter_value_id')->constrained('filter_values')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['category_id', 'filter_value_id']);
        });

        // Seed initial data: For any filter group associated with a category, associate all of its values.
        $categoryGroups = DB::table('category_filter_group')->get();
        foreach ($categoryGroups as $cg) {
            $values = DB::table('filter_values')
                ->where('filter_group_id', $cg->filter_group_id)
                ->get();

            foreach ($values as $val) {
                DB::table('category_filter_value')->insertOrIgnore([
                    'category_id' => $cg->category_id,
                    'filter_value_id' => $val->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_filter_value');
    }
};
