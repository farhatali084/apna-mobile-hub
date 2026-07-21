<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'button_text',
        'button_link',
        'image_path',
        'is_active',
        'display_order',
    ];

    /**
     * Get the formatted image URL.
     */
    public function getImageUrl()
    {
        $value = $this->image_path;

        if (empty($value)) {
            return '';
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        $cleanPath = ltrim($value, '/');

        // Check if path starts with images/ or storage/
        if (str_starts_with($cleanPath, 'images/') || str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        // Default fallback to storage/
        return asset('storage/' . $cleanPath);
    }
}
