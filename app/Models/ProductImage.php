<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image_path', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

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
}
