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
        Schema::create('access_policies', function (Blueprint $table) {
            Schema::create('access_policies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')
                    ->constrained()
                    ->cascadeOnDelete();
                $table->foreignId('branch_id')
                    ->constrained()
                    ->cascadeOnDelete();
                $table->enum('access_type', [
                    'off_peak',
                    'daytime',
                    'full'
                ]);
                $table->time('start_time');
                $table->time('end_time');
                $table->json('days_of_week');
                $table->boolean('is_active')
                    ->default(true);

                $table->timestamps();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_policies');
    }
};
