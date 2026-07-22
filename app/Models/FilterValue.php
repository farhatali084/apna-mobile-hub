<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterValue extends Model
{
    protected $fillable = ['filter_group_id', 'value', 'color_hex', 'min_qty'];

    protected $casts = [
        'min_qty' => 'integer',
    ];


    /**
     * Get the filter group that owns the value.
     */
    public function group()
    {
        return $this->belongsTo(FilterGroup::class, 'filter_group_id');
    }

    /**
     * Get the products associated with this filter value tag.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_filter_value');
    }
}
