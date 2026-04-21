<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DietPlanMeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'diet_plan_id',
        'day_of_week',
        'meal_type',
        'recipe_name',
        'ingredients',
        'macros',
        'total_calories',
        'suggested_time',
    ];

    protected $casts = [
        'ingredients'     => 'array',
        'macros'          => 'array',
        'suggested_time'  => 'datetime:H:i',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Parent Diet Plan
    public function dietPlan()
    {
        return $this->belongsTo(DietPlan::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Constants (clean usage)
    |--------------------------------------------------------------------------
    */

    public const MEAL_BREAKFAST = 'breakfast';
    public const MEAL_LUNCH     = 'lunch';
    public const MEAL_DINNER    = 'dinner';
    public const MEAL_SNACK     = 'snack';

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function getDayNameAttribute(): string
    {
        return match ($this->day_of_week) {
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
            default => 'Unknown',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeByDay($query, int $day)
    {
        return $query->where('day_of_week', $day);
    }

    public function scopeByMealType($query, string $type)
    {
        return $query->where('meal_type', $type);
    }
}
