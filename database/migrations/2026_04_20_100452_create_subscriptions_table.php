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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('membership_plan_id')->constrained();

            $table->string('status'); // active, cancelled, expired, past_due
            $table->string('stripe_subscription_id')->nullable()->index();

            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable(); // When access should be cut off
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
