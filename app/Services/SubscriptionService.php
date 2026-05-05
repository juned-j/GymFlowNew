<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Branch;
use App\Models\Member;
use App\Models\Trainer;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    /**
     * Check if tenant reached limit for a module
     */
    public function reachedLimit(Tenant $tenant, string $type): bool
    {
        $type = strtolower(trim($type));

        Log::info('🔍 CHECK LIMIT START', [
            'tenant_id' => $tenant->id,
            'type' => $type,
        ]);

        // ✅ Load subscription
        $subscription = $tenant->subscription;

        Log::info('📦 SUBSCRIPTION DATA', [
            'exists' => (bool) $subscription,
            'status' => $subscription?->status,
            'saas_plan_id' => $subscription?->saas_plan_id,
        ]);

        // ❗ No subscription → free access
        if (!$subscription) {
            Log::warning('⚠️ NO SUBSCRIPTION FOUND → ALLOW');
            return false;
        }

        // ❌ Inactive subscription → block
        if (method_exists($subscription, 'isActive') && !$subscription->isActive()) {
            Log::warning('⛔ SUBSCRIPTION NOT ACTIVE → BLOCK');
            return true;
        }

        // ✅ Load plan
        $plan = $subscription->plan;

        Log::info('📊 PLAN DATA', [
            'plan_exists' => (bool) $plan,
            'plan_id' => $plan?->id,
        ]);

        if (!$plan) {
            Log::error('❌ PLAN NOT FOUND → BLOCK');
            return true;
        }

        // 🧠 Allowed types only (prevents abuse / bugs)
        $allowedTypes = ['branches', 'members', 'trainers'];

        if (!in_array($type, $allowedTypes)) {
            Log::error('❌ INVALID LIMIT TYPE', ['type' => $type]);
            return true;
        }

        // ✅ Dynamic limit field
        $limitField = "max_{$type}";
        $limit = data_get($plan, $limitField);

        Log::info('📏 LIMIT CHECK', [
            'field' => $limitField,
            'limit' => $limit,
        ]);

        // ♾️ Unlimited plan
        if (empty($limit)) {
            Log::info('♾️ UNLIMITED PLAN → ALLOW');
            return false;
        }

        // ✅ Usage count
        $count = match ($type) {
            'branches' => Branch::where('tenant_id', $tenant->id)->count(),
            'members'  => Member::where('tenant_id', $tenant->id)->count(),
            'trainers' => Trainer::where('tenant_id', $tenant->id)->count(),
        };

        Log::info('📊 USAGE COUNT', [
            'count' => $count,
            'limit' => $limit,
        ]);

        // 🚫 Final check
        $limitReached = $count >= (int) $limit;

        Log::info('🚫 FINAL RESULT', [
            'limitReached' => $limitReached,
        ]);

        return $limitReached;
    }
}