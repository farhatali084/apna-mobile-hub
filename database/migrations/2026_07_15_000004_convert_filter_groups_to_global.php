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
        // 1. Create category_filter_group pivot table
        Schema::create('category_filter_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('filter_group_id')->constrained('filter_groups')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['category_id', 'filter_group_id']);
        });

        // 2. Migrate existing category-filter relations
        $existingGroups = DB::table('filter_groups')
            ->whereNotNull('category_id')
            ->get();

        foreach ($existingGroups as $group) {
            DB::table('category_filter_group')->insert([
                'category_id' => $group->category_id,
                'filter_group_id' => $group->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Drop category_id foreign key and column from filter_groups
        Schema::table('filter_groups', function (Blueprint $table) {
            // Drop foreign key first (SQLite doesn't always strictly require key names, but for MySQL we use table_col_foreign syntax)
            try {
                $table->dropForeign(['category_id']);
            } catch (\Exception $e) {
                // Fallback for different DB drivers
            }
            $table->dropColumn('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back category_id column
        Schema::table('filter_groups', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
        });

        // Restore column values from pivot
        $pivotRelations = DB::table('category_filter_group')->get();
        foreach ($pivotRelations as $rel) {
            DB::table('filter_groups')
                ->where('id', $rel->filter_group_id)
                ->update(['category_id' => $rel->category_id]);
        }

        // Drop pivot table
        Schema::dropIfExists('category_filter_group');
    }
};
