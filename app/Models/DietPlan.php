<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DietPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'trainer_id',
        'name',
        'description',
        'goal',
        'is_template',
    ];

    protected $casts = [
        'is_template' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Tenant (Gym / Organization)
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Trainer (created by)
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    // Meals inside this diet plan
    public function meals()
    {
        return $this->hasMany(DietPlanMeal::class);
    }

    // Members assigned to this plan
    public function memberPlans()
    {
        return $this->hasMany(MemberDietPlan::class);
    }
    

}
