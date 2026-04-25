<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_code',
        'currency_name',
        'exchange_rate',
        'currency_symbol',
        'is_default',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:4',
        'is_default' => 'boolean',
    ];

    /**
     * Scope: Default currency
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
