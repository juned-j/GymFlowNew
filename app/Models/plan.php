<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'monthly_price',
        'annual_price',
   
        'max_users',
        'is_custom',
        'features',
        'stripe_product_id',
        'stripe_monthly_price_id',
        'stripe_annual_price_id',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_custom' => 'boolean',
        'is_active' => 'boolean',
    ];
}
