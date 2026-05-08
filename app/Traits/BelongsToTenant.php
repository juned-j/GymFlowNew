<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        /**
         * AUTO ASSIGN TENANT ID ON CREATE
         */
        static::creating(function ($model) {

            if (auth()->check() && empty($model->tenant_id)) {
                $model->tenant_id = auth()->user()?->getTenantId();
            }
        });

        /**
         * GLOBAL SCOPE (SAFE + MODEL-AWARE)
         */
        static::addGlobalScope('tenant', function (Builder $builder) {

            $model = $builder->getModel();

            // ❌ Skip if model explicitly disables tenant scope
            if (property_exists($model, 'withoutTenantScope') && $model::$withoutTenantScope) {
                return;
            }

            $user = auth()->user();

            // ❌ No auth = no filtering (prevents broken Filament / system queries)
            if (!$user) {
                return;
            }

            $tenantId = $user->getTenantId();

            if (!empty($tenantId)) {
                $builder->where(
                    $model->getTable() . '.tenant_id',
                    $tenantId
                );
            }
        });
    }
}
