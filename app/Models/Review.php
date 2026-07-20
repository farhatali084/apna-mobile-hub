<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_designation',
        'review_text',
        'rating',
        'avatar_path',
        'display_order',
        'is_active',
    ];

    /**
     * Get user avatar URL or default placeholder
     */
    public function getAvatarUrl(): string
    {
        if ($this->avatar_path) {
            return asset('storage/' . $this->avatar_path);
        }
        // Fallback default avatar placeholder
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->customer_name) . '&background=EFF6FF&color=2563EB&bold=true';
    }
}
