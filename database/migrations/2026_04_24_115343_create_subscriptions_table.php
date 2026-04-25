<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->string('stripe_subscription_id')->nullable();

            $table->string('status');

            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('tenant_id');

            $table->timestamps();

            // Foreign keys (optional but recommended)
            $table->foreign('plan_id')
                ->references('id')
                ->on('membership_plans')
                ->onDelete('cascade');

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
