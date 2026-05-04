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
        Schema::table('exercise_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('exercise_logs', 'is_completed')) {
                $table->boolean('is_completed')
                    ->default(false)
                    ->after('your_existing_column'); // change this if needed
            }

            if (!Schema::hasColumn('exercise_logs', 'notes')) {
                $table->text('notes')
                    ->nullable()
                    ->after('is_completed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercise_logs', function (Blueprint $table) {
            if (Schema::hasColumn('exercise_logs', 'notes')) {
                $table->dropColumn('notes');
            }

            if (Schema::hasColumn('exercise_logs', 'is_completed')) {
                $table->dropColumn('is_completed');
            }
        });
    }
};
