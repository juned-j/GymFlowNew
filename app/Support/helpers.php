<?php

if (!function_exists('tenantId')) {
    /**
     * Get the current tenant ID from session or user context.
     *
     * @return int|string|null
     */
    function tenantId()
    {
        if (session()->has('tenant_id')) {
            return session('tenant_id');
        }

        if (auth()->check()) {
            $user = auth()->user();

            if ($user->isSuperAdmin()) {
                return null;
            }

            // Fallback to database lookup (for jobs or lost sessions)
            return $user->roles()
                ->whereHas('role', fn($q) => $q->where('name', 'owner'))
                ->value('tenant_id')
                ?? $user->roles()->value('tenant_id');
        }

        return null;
    }
}
