<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Branch;
use App\Models\Member;
use App\Models\GymPartnership;
use Illuminate\Support\Facades\Log;

class GymPartnershipService
{

    public function sendInvite($home, $partner, $percent)
    {
        // prevent duplicates
        if ($this->exists($home, $partner)) {
            return;
        }

        $forward = GymPartnership::create([
            'tenant_id' => $home,
            'partner_tenant_id' => $partner,
            'status' => 'pending',
            'revenue_share_percent' => $percent,
        ]);

        $inverse = GymPartnership::create([
            'tenant_id' => $partner,
            'partner_tenant_id' => $home,
            'status' => 'pending',
            'revenue_share_percent' => 100 - $percent,
        ]);

        return [$forward, $inverse];
    }
    public function accept(GymPartnership $p)
    {
        $this->transition($p, 'active');

        GymPartnership::where([
            'tenant_id' => $p->partner_tenant_id,
            'partner_tenant_id' => $p->tenant_id,
        ])->update(['status' => 'active']);
    }
    public function reject(GymPartnership $p)
    {
        $this->transition($p, 'rejected');

        GymPartnership::where([
            'tenant_id' => $p->partner_tenant_id,
            'partner_tenant_id' => $p->tenant_id,
        ])->update(['status' => 'rejected']);
    }
    public function suspend(GymPartnership $p)
    {
        $this->transition($p, 'suspended');

        GymPartnership::where([
            'tenant_id' => $p->partner_tenant_id,
            'partner_tenant_id' => $p->tenant_id,
        ])->update(['status' => 'suspended']);
    }
    private function transition($p, $newStatus)
    {
        $allowed = [
            'pending' => ['active', 'rejected'],
            'active' => ['suspended'],
            'suspended' => ['active'],
        ];

        if (!in_array($newStatus, $allowed[$p->status] ?? [])) {
            throw new \Exception("Invalid state transition");
        }

        $p->update(['status' => $newStatus]);
    }
}
