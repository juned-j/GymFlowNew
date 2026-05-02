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
        Schema::table('members', function (Blueprint $table) {

            // Add BMI column
            if (!Schema::hasColumn('members', 'bmi')) {
                $table->float('bmi')->nullable()->after('weight');
            }

            // ✅ Add BMI Category column
            if (!Schema::hasColumn('members', 'bmi_category')) {
                $table->string('bmi_category', 20)
                    ->nullable()
                    ->after('bmi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {

            if (Schema::hasColumn('members', 'bmi_category')) {
                $table->dropColumn('bmi_category');
            }

            if (Schema::hasColumn('members', 'bmi')) {
                $table->dropColumn('bmi');
            }
        });
    }
};
