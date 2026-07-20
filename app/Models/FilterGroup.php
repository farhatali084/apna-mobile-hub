<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FilterGroup extends Model
{
    protected $fillable = ['name', 'slug', 'display_order'];

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
     * Get the categories associated with this filter group.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_filter_group');
    }

    /**
     * Get the values for the filter group.
     */
    public function values()
    {
        return $this->hasMany(FilterValue::class);
    }
}
