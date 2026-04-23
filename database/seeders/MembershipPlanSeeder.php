<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('membership_plans')->insert([

            // 🟢 FREE PLAN
            [
                'tenant_id' => 1,
                'name' => 'Free Plan',
                'price' => 0,
                'billing_period' => 'month',
                'workout_plan_limit' => 1,
                'has_trainer_support' => false,
                'is_active' => true,

                'stripe_product_id' => null,
                'stripe_price_id' => null,

                'features' => json_encode([
                    'max_workouts_per_week' => 3,
                    'workout_plans' => 1,
                    'exercise_library' => 'limited',
                    'progress_tracking' => 'basic',
                    'ai_plans' => false,
                    'meal_plans' => false,
                    'trainer_support' => false,
                ]),

                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🔵 PRO MONTHLY
            [
                'tenant_id' => 1,
                'name' => 'Pro Monthly',
                'price' => 499,
                'billing_period' => 'month',
                'workout_plan_limit' => 999,
                'has_trainer_support' => false,
                'is_active' => true,

                'stripe_product_id' => null,
                'stripe_price_id' => null,

                'features' => json_encode([
                    'workout_plans' => 'unlimited',
                    'exercise_library' => 'full',
                    'progress_tracking' => 'advanced',
                    'ai_plans' => true,
                    'meal_plans' => 'basic',
                    'workout_history' => true,
                    'insights' => true,
                ]),

                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🔵 PRO YEARLY
            [
                'tenant_id' => 1,
                'name' => 'Pro Yearly',
                'price' => 4999,
                'billing_period' => 'year',
                'workout_plan_limit' => 999,
                'has_trainer_support' => false,
                'is_active' => true,

                'stripe_product_id' => null,
                'stripe_price_id' => null,

                'features' => json_encode([
                    'workout_plans' => 'unlimited',
                    'exercise_library' => 'full',
                    'progress_tracking' => 'advanced',
                    'ai_plans' => true,
                    'meal_plans' => 'basic',
                    'workout_history' => true,
                    'insights' => true,
                ]),

                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🔥 ELITE MONTHLY
            [
                'tenant_id' => 1,
                'name' => 'Elite Monthly',
                'price' => 999,
                'billing_period' => 'month',
                'workout_plan_limit' => 999,
                'has_trainer_support' => true,
                'is_active' => true,

                'stripe_product_id' => null,
                'stripe_price_id' => null,

                'features' => json_encode([
                    'workout_plans' => 'unlimited',
                    'exercise_library' => 'full',
                    'progress_tracking' => 'advanced',
                    'ai_plans' => true,
                    'meal_plans' => 'advanced',
                    'trainer_support' => true,
                    'custom_plans' => true,
                    'live_classes' => true,
                    'priority_support' => true,
                ]),

                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🔥 ELITE YEARLY
            [
                'tenant_id' => 1,
                'name' => 'Elite Yearly',
                'price' => 9999,
                'billing_period' => 'year',
                'workout_plan_limit' => 999,
                'has_trainer_support' => true,
                'is_active' => true,

                'stripe_product_id' => null,
                'stripe_price_id' => null,

                'features' => json_encode([
                    'workout_plans' => 'unlimited',
                    'exercise_library' => 'full',
                    'progress_tracking' => 'advanced',
                    'ai_plans' => true,
                    'meal_plans' => 'advanced',
                    'trainer_support' => true,
                    'custom_plans' => true,
                    'live_classes' => true,
                    'priority_support' => true,
                ]),

                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
