<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        /**
         * AUTO ASSIGN TENANT ID
         */
        static::creating(function ($model) {

            if (
                auth()->check() &&
                empty($model->tenant_id)
            ) {
                $model->tenant_id = auth()->user()->getTenantId();
            }
        });

        /**
         * GLOBAL TENANT SCOPE
         */
        static::addGlobalScope('tenant', function (Builder $builder) {

            if (auth()->check()) {

                $tenantId = auth()->user()->getTenantId();

                if ($tenantId) {

                    $builder->where(
                        $builder->getModel()->getTable() . '.tenant_id',
                        $tenantId
                    );
                }
            }
        });
    }
}