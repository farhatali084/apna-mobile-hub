<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
    protected $fillable = ['name', 'slug', 'logo', 'is_active', 'sort_order'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * Get image / logo URL with fallback
     */
    public function getImageUrl(): ?string
    {
        if ($this->logo) {
            if (Str::startsWith($this->logo, ['http://', 'https://'])) {
                return $this->logo;
            }
            $cleanPath = ltrim($this->logo, '/');
            if (Str::startsWith($cleanPath, ['images/', 'storage/'])) {
                return asset($cleanPath);
            }
            return Storage::url($this->logo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=fff&color=000&format=svg';
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->getImageUrl();
    }
}
