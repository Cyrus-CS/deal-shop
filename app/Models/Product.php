<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'regular_price',
        'image',
        'images',
        'sale_price',
        'description',
        'short_description',
        'SKU',
        'stock_status',
        'featured',
        'quantity',
        'category_id',
        'brand_id',
    ];

    public function category() : BelongsTo {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand() : BelongsTo {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
