<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait HasPlanRestrictions
{
    /**
     * Smart limit checker (returns status + message)
     */
    public function checkLimit(string $type, callable $countResolver): array
    {
        $map = [
            'branches' => 'max_branches',
            'trainers' => 'max_trainers',
            'members'  => 'max_members',
        ];

        if (!isset($map[$type])) {
            return [
                'allowed' => true,
                'message' => "Invalid limit type: {$type}",
            ];
        }

        $plan = $this->plan ?? null;

        if (!$plan) {
            return [
                'allowed' => false,
                'message' => 'No plan assigned. Please contact admin.',
            ];
        }

        $limitColumn = $map[$type];
        $limit = $plan->{$limitColumn};

        Log::info("📊 Plan Limit Check", [
            'type' => $type,
            'limit' => $limit,
        ]);

        // 🚨 Missing config (IMPORTANT)
        if ($limit === null) {
            return [
                'allowed' => false,
                'message' => ucfirst($type) . ' limit is not configured in your plan.',
            ];
        }

        // unlimited
        if ($limit == 0) {
            return [
                'allowed' => true,
                'message' => null,
            ];
        }

        $count = $countResolver();

        Log::info("📈 Usage Check", [
            'count' => $count,
            'limit' => $limit,
        ]);

        if ($count >= $limit) {
            return [
                'allowed' => false,
                'message' => ucfirst($type) . ' limit reached. Upgrade your plan.',
            ];
        }

        return [
            'allowed' => true,
            'message' => null,
        ];
    }

    /**
     * Simple relation count
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