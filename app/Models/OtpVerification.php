<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class OtpVerification extends Model
{
    protected $table = 'otp_verifications';

    protected $fillable = [
        'phone',
        'otp_hash',
        'expires_at',
        'verified_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // Get latest active OTP for a phone
    public function scopeActive(Builder $query, string $phone): Builder
    {
        return $query->where('phone', $phone)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function markAsUsed(): void
    {
        $this->update([
            'is_used' => true,
            'verified_at' => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATIC METHODS (CORE LOGIC)
    |--------------------------------------------------------------------------
    */

    public static function generate(string $phone): array
    {
        // Invalidate old OTPs
        self::where('phone', $phone)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        $otp = rand(100000, 999999);

        $record = self::create([
            'phone' => $phone,
            'otp_hash' => Hash::make($otp),
            'expires_at' => now()->addMinutes(5),
        ]);

        return [
            'otp' => $otp, // send via SMS
            'record' => $record,
        ];
    }

    public static function verify(string $phone, string $otp): bool
    {
        $record = self::active($phone)->first();

        if (!$record) {
            return false;
        }

        if ($record->isExpired()) {
            return false;
        }

        if (!Hash::check($otp, $record->otp_hash)) {
            return false;
        }

        $record->markAsUsed();

        return true;
    }
}
