<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {

            // Program duration
            $table->string('duration_type')
                ->nullable()
                ->after('capacity');
            // weeks, months, years

            $table->integer('duration_value')
                ->nullable()
                ->after('duration_type');
            // 1, 3, 6, 12

            // Recurrence
            $table->string('repeat_type')
                ->nullable()
                ->after('duration_value');
            // daily, weekly, monthly

            // Schedule dates
            $table->date('start_date')
                ->nullable()
                ->after('repeat_type');

            $table->date('end_date')
                ->nullable()
                ->after('start_date');

            // Days of week
            $table->json('days_of_week')
                ->nullable()
                ->after('end_date');
            // ["mon","wed","fri"]

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {

            $table->dropColumn([
                'duration_type',
                'duration_value',
                'repeat_type',
                'start_date',
                'end_date',
                'days_of_week',
            ]);
        });
    }
};
