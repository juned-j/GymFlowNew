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
        Schema::table('workout_plans', function (Blueprint $table) {
            $table->enum('goal_type', [
                'fat_loss',
                'muscle_gain',
                'strength'
            ])->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workout_plans', function (Blueprint $table) {
            $table->dropColumn('goal_type');
        });
    }
};
