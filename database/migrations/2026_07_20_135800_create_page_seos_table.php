<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_seos', function (Blueprint $table) {
            $table->id();
            $table->string('page_identifier')->unique()->comment('Unique key: home, about, contact, etc.');
            $table->string('page_name')->comment('Admin-friendly label');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('robots')->default('index, follow');
            $table->string('canonical_url')->nullable();
            $table->longText('schema_markup')->nullable()->comment('Custom JSON-LD structured data');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_seos');
    }
};
