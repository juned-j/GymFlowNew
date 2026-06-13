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
        Schema::table('members', function (Blueprint $blueprint) {
            // Adds onboarding step defaulting to step 1
            $blueprint->integer('onboarding_step')->default(1)->after('status');
            
            // Adds completion tracking defaulting to false
            $blueprint->boolean('is_onboarding_completed')->default(false)->after('onboarding_step');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['onboarding_step', 'is_onboarding_completed']);
        });
    }
};
