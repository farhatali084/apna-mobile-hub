<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image_path',
        'category_id',
        'brand_id',
        'stock',
        'is_featured',
        'is_top_deal',
        'is_bestseller',
        'rating',
        'rating_count',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_top_deal' => 'boolean',
        'is_bestseller' => 'boolean',
    ];

    /**
     * Get the formatted image URL.
     */
    public function getImageUrl()
    {
        $value = $this->image_path;

        if (empty($value)) {
            return 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&q=80';
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, '/images/')) {
            return $value;
        }

        return asset('storage/' . $value);
    }

    /**
     * Get gallery images for this product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Get all image URLs (primary + gallery) for carousel rendering.
     */
    public function getAllImageUrls()
    {
        $urls = [$this->getImageUrl()];

        foreach ($this->images as $img) {
            $url = $img->getImageUrl();
            if ($url && !in_array($url, $urls)) {
                $urls[] = $url;
            }
        }

        return $urls;
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that owns the product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the filter value tags for this product.
     */
    public function filterValues()
    {
        return $this->belongsToMany(FilterValue::class, 'product_filter_value');
    }

    /**
     * Scope query to only include products matching all selected filter value tags.
     */
    public function scopeMatchingFilters($query, array $filterValueIds)
    {
        return $query->whereHas('filterValues', function($q) use ($filterValueIds) {
            $q->whereIn('filter_values.id', $filterValueIds);
        });
    }
}
