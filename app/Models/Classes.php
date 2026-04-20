<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classes extends Model
{
    // Since 'classes' is the table name and the model is 'Classes', 
    // Laravel usually finds it, but defining it explicitly is safer.
    protected $table = 'classes';

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'trainer_id',
        'name',
        'description',
        'start_time',
        'end_time',
        'capacity',
        'location',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'capacity' => 'integer',
        ];
    }

    /**
     * The Gym (Tenant) this class belongs to.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * The specific Branch where this class is held.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * The Trainer (User) leading the class.
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    /**
     * Get all bookings for this class.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'class_id');
    }
}
