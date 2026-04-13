<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = auth()->user();

        $role = $user->roles->first()?->name;

        return match ($role) {
            'super_admin' => redirect('/platform'),
            'owner'       => redirect('/admin'),
            'trainer'     => redirect('/admin'),
            default        => redirect('/admin'),
        };
    }
}
