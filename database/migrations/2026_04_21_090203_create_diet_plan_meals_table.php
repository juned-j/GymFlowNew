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
        Schema::create('diet_plan_meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diet_plan_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week'); // 1 (Mon) to 7 (Sun)
            $table->string('meal_type'); // Breakfast, Lunch, etc.
            $table->string('recipe_name');
            $table->json('ingredients')->nullable(); // Store list of items/quantities
            $table->json('macros')->nullable();      // {"protein": "30g", "carbs": "50g"}
            $table->integer('total_calories')->default(0);
            $table->time('suggested_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diet_plan_meals');
    }
};
