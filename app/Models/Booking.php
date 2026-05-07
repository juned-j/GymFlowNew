<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Classes;
use App\Traits\BelongsToTenant;

class Booking extends Model
{

     use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'class_id',
        'status',
    ];

    /**
     * Get the gym (tenant) this booking belongs to.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the user (member) who made the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the specific class that was booked.
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
