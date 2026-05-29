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
        Schema::create('gym_partnerships', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            $table->foreignId('partner_tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            $table->enum('status', [
                'pending',
                'active',
                'rejected'
            ])->default('pending');

            $table->decimal('revenue_share_percent', 5, 2)
                ->default(50);

            $table->timestamps();

            $table->unique([
                'tenant_id',
                'partner_tenant_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_partnerships');
    }
};
