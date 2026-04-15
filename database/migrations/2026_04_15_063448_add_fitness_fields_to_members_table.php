<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('age', 50)->nullable()->after('weight');
            $table->string('fitness_level', 50)->nullable()->after('age');
            $table->string('activity_level', 50)->nullable()->after('fitness_level');
            $table->text('injuries')->nullable()->after('activity_level');
            $table->string('program_match', 100)->nullable()->after('injuries');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'age',
                'fitness_level',
                'activity_level',
                'injuries',
                'program_match',
            ]);
        });
    }
};
