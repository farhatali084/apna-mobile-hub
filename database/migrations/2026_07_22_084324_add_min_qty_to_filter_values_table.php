<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds min_qty (Minimum Order Quantity) to filter_values.
     * Default = 1 (safe for existing records).
     */
    public function up(): void
    {
        Schema::table('filter_values', function (Blueprint $table) {
            $table->unsignedInteger('min_qty')->default(1)->after('color_hex');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filter_values', function (Blueprint $table) {
            $table->dropColumn('min_qty');
        });
    }
};
