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
        Schema::create('member_network_access', function (Blueprint $table) {
            $table->id();

            $table->foreignId('member_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('subscription_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('upgrade_tier_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->date('valid_from');
            $table->date('valid_to');
            $table->boolean('is_active')
                ->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_network_access');
    }
};
