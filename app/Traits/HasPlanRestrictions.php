<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait HasPlanRestrictions
{
    /**
     * Generic plan limit checker (GYM FLOW READY)
     */
    public function reachedLimit(string $type, callable $countResolver): bool
    {
        $map = [
            'branches' => 'max_branches',
            'trainers' => 'max_trainers',
            'members'  => 'max_members',
        ];

        if (!isset($map[$type])) {
            Log::warning("Invalid limit type: {$type}");
            return false;
        }

        $plan = $this->plan ?? null;

        if (!$plan) {
            Log::warning("❌ No plan found for tenant/company");
            return false;
        }

        $limitColumn = $map[$type];
        $limit = $plan->{$limitColumn} ?? null;

        Log::info("📊 Plan Limit Check", [
            'type' => $type,
            'limit' => $limit,
        ]);

        if (!$limit || $limit == 0) {
            return false; // unlimited
        }

        $count = $countResolver();

        Log::info("📈 Usage Check", [
            'count' => $count,
            'limit' => $limit,
        ]);

        return $count >= $limit;
    }

    /**
     * Simple shortcut (NO closure needed)
     */
    public function usageCount(string $relation): int
    {
        return method_exists($this, $relation)
            ? $this->$relation()->count()
            : 0;
    }

    public function canUse(string $feature): bool
    {
        return in_array($feature, $this->plan->features ?? []);
    }
}