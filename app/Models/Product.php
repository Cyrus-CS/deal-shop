<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    // protected $casts = ['images' => 'array'];
    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'images' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /** Gallery images  */
    protected function gallery(): Attribute
    {
        return Attribute::get(fn () => collect($this->images ?? [])
            ->filter()
            ->reject(fn ($img) => $img === $this->image)
            ->values());
    }

    /** Image principale + galerie (pour le slider de la page détail) */
    protected function allImages(): Attribute
    {
        return Attribute::get(fn () => $this->gallery->prepend($this->image)->filter()->values());
    }

    // -------------------------------- SCOPE ------------------------------
    public function scopeFeatured(Builder $query) : Builder {
        return $query->where('featured', true);
    } 
}
