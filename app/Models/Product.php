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
        'stock',
        'is_featured',
        'rating',
        'rating_count',
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
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
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
