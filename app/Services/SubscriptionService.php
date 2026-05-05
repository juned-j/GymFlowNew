<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Branch;
use App\Models\Member;
use App\Models\Trainer;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    public function reachedLimit(Tenant $tenant, string $type): bool
    {
        Log::info('🔍 CHECK LIMIT START', [
            'tenant_id' => $tenant->id,
            'type' => $type,
        ]);

        // ✅ 1. Subscription load safely
        $subscription = $tenant->subscription;

        Log::info('📦 SUBSCRIPTION DATA', [
            'exists' => (bool) $subscription,
            'status' => $subscription?->status,
            'saas_plan_id' => $subscription?->saas_plan_id,
        ]);

        // ❗ No subscription → allow (free tier)
        if (!$subscription) {
            Log::warning('⚠️ NO SUBSCRIPTION FOUND → allow');
            return false;
        }

        // ❌ Not active → block
        if (!$subscription->isActive()) {
            Log::warning('⛔ SUBSCRIPTION NOT ACTIVE → block');
            return true;
        }

        // ✅ 2. Plan load safely
        $plan = $subscription->plan;

        Log::info('📊 PLAN DATA', [
            'plan_exists' => (bool) $plan,
            'plan_id' => $plan?->id,
            'max_branches' => $plan?->max_branches,
            'max_members' => $plan?->max_members,
            'max_trainers' => $plan?->max_trainers,
        ]);

        // ❌ No plan → block (safety)
        if (!$plan) {
            Log::error('❌ PLAN NOT FOUND → block');
            return true;
        }

        // ✅ 3. Dynamic limit field
        $limitField = "max_{$type}";
        $limit = $plan->{$limitField} ?? null;

        Log::info('📏 LIMIT CHECK', [
            'field' => $limitField,
            'limit' => $limit,
        ]);

        // ♾️ Unlimited
        if ($limit === null || (int) $limit === 0) {
            Log::info('♾️ UNLIMITED PLAN');
            return false;
        }

        // ✅ 4. Count usage
        $count = match ($type) {
            'branches' => Branch::where('tenant_id', $tenant->id)->count(),
            'members'  => Member::where('tenant_id', $tenant->id)->count(),
            'trainers' => Trainer::where('tenant_id', $tenant->id)->count(),
            default    => 0,
        };

        Log::info('📊 USAGE COUNT', [
            'count' => $count,
        ]);

        // ✅ 5. Final result
        $limitReached = $count >= (int) $limit;

        Log::info('🚫 FINAL RESULT', [
            'limitReached' => $limitReached,
        ]);

        return $limitReached;
    }
}