<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GymVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'tenant_id',
        'branch_id',
        'check_in_time',
        'check_out_time',
        'access_type_used',
    ];

    protected $casts = [
        'check_in_time'  => 'datetime',
        'check_out_time' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Member who visited the gym
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Gym / tenant visited
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Branch visited
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Revenue shares for this visit
     */
    public function revenueShares()
    {
        return $this->hasMany(RevenueShare::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->whereNull('check_out_time');
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('check_out_time');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('check_in_time', now()->toDateString());
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if member is currently inside gym
     */
    public function isActive(): bool
    {
        return is_null($this->check_out_time);
    }

    /**
     * Calculate visit duration in minutes
     */
    public function getDurationInMinutesAttribute(): ?int
    {
        if (!$this->check_out_time) {
            return null;
        }

        return Carbon::parse($this->check_in_time)
            ->diffInMinutes(Carbon::parse($this->check_out_time));
    }

    /**
     * Calculate visit duration in hours
     */
    public function getDurationInHoursAttribute(): ?float
    {
        if (!$this->check_out_time) {
            return null;
        }

        return round(
            Carbon::parse($this->check_in_time)
                ->diffInMinutes(Carbon::parse($this->check_out_time)) / 60,
            2
        );
    }
}