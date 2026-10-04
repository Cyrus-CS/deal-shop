<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperBrand
 */
class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
    ];
}
