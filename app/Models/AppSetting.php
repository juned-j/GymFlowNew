<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = ['settings'];

    protected $casts = [
        'settings' => 'array',
    ];
}