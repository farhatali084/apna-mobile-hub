<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_top_deal')->default(false)->after('is_featured');
            $table->boolean('is_bestseller')->default(false)->after('is_top_deal');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_top_deal', 'is_bestseller']);
        });
    }
};
