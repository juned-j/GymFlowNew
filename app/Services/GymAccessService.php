<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Tenant;
use App\Models\AccessPolicy;
use App\Models\GymPartnership;
use App\Models\MemberNetworkAccess;
use Carbon\Carbon;

class GymAccessService
{
    /**
     * Validate if member can access gym
     */
    public function validateAccess(
        Member $member,
        Tenant $tenant
    ) {
        // 1. Active network access
        $access = MemberNetworkAccess::where('member_id', $member->id)
            ->where('is_active', true)
            ->first();

        if (!$access) {
            return false;
        }

        // 2. Partnership exists
        $partnership = GymPartnership::where(function ($q) use ($tenant, $member) {

            $q->where('tenant_id', $member->tenant_id)
                ->where('partner_tenant_id', $tenant->id);
        })->where('status', 'active')
            ->exists();

        if (!$partnership) {
            return false;
        }

        // 3. Validate time policy
        $policy = AccessPolicy::where('tenant_id', $tenant->id)
            ->where('access_type', $access->upgradeTier->access_level)
            ->where('is_active', true)
            ->first();

        if (!$policy) {
            return false;
        }

        return true;
    }
}
