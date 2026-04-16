<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return redirect('/platform');
        }

        // Set tenant session
        $tenantId = $user->roles()
            ->whereHas('role', fn($q) => $q->where('name', 'owner'))
            ->value('tenant_id')
            ?? $user->roles()->value('tenant_id');

        session(['tenant_id' => $tenantId]);

        return redirect('/admin');
    }
}
