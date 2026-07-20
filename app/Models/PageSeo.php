<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageSeo extends Model
{
    protected $fillable = [
        'page_identifier',
        'page_name',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'robots',
        'canonical_url',
        'schema_markup',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get SEO data for a specific page identifier.
     * Results are cached for 1 hour for performance.
     */
    public static function forPage(string $identifier): ?self
    {
        return Cache::remember("page_seo_{$identifier}", 3600, function () use ($identifier) {
            return static::where('page_identifier', $identifier)
                ->where('is_active', true)
                ->first();
        });
    }

    /**
     * Clear the cache when a record is saved or deleted.
     */
    protected static function booted(): void
    {
        static::saved(function (PageSeo $seo) {
            Cache::forget("page_seo_{$seo->page_identifier}");
        });

        static::deleted(function (PageSeo $seo) {
            Cache::forget("page_seo_{$seo->page_identifier}");
        });
    }

    /**
     * Get the full URL for the OG image.
     */
    public function getOgImageUrlAttribute(): ?string
    {
        if ($this->og_image) {
            return asset('storage/' . $this->og_image);
        }
        return null;
    }
}
