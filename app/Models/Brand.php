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

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) {
            return Storage::url($this->logo);
        }
        return null;
    }
}
