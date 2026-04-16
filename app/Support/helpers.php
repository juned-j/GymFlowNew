<?php

if (!function_exists('tenantId')) {
    /**
     * Get the current tenant ID from session or user context.
     *
     * @return int|string|null
     */
    function tenantId()
    {
        if (auth()->check()) {
            return auth()->user()->getTenantId();
        }

        return null;
    }
}
