<?php

namespace App\Policies;

use App\Models\GymPartnership;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GymPartnershipPolicy
{
    public function accept(User $user, GymPartnership $p)
    {
        return $user->gym_id === $p->partner_tenant_id;
    }

    public function reject(User $user, GymPartnership $p)
    {
        return $user->gym_id === $p->partner_tenant_id;
    }

    public function update(User $user, GymPartnership $p)
    {
        return $user->gym_id === $p->tenant_id;
    }
}
