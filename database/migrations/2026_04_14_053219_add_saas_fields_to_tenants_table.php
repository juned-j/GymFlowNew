<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {

            // Contact & identity
            $table->string('email')->nullable()->after('logo_url');
            $table->string('phone')->nullable()->after('email');

            // Business location metadata
            $table->text('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->after('city');

            // System configuration
            $table->string('timezone')->default('Asia/Kolkata')->after('country');
            $table->string('currency')->default('INR')->after('timezone');

            // Status control
            $table->boolean('is_active')->default(true)->after('status');
            $table->timestamp('trial_ends_at')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {

            $table->dropColumn([
                'email',
                'phone',
                'address',
                'city',
                'country',
                'timezone',
                'currency',
                'is_active',
                'trial_ends_at',
            ]);
        });
    }
};
