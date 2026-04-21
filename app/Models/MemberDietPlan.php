<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\DietPlan;

class MemberDietPlan extends Model
{
    use HasFactory;

    protected $table = 'member_diet_plans';

    protected $fillable = [
        'user_id',
        'diet_plan_id',
        'assigned_by',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Member (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Diet Plan
    public function dietPlan()
    {
        return $this->belongsTo(DietPlan::class);
    }

    // Trainer who assigned
    public function assignedBy()
    {
        return $this->belongsTo(Trainer::class, 'assigned_by');
    }
}
