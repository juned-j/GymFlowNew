<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., "Elite Monthly"
            $table->decimal('price', 10, 2);
            $table->enum('billing_period', ['month', 'year'])->default('month');
            $table->integer('workout_plan_limit')->default(1);
            $table->boolean('has_trainer_support')->default(false);
            $table->boolean('is_active')->default(true); // Toggle plan visibility
              $table->string('stripe_product_id')->nullable()->after('is_active');
        $table->string('stripe_price_id')->nullable()->after('stripe_product_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_plans');
                $table->dropColumn(['stripe_product_id', 'stripe_price_id']);

    }
};
