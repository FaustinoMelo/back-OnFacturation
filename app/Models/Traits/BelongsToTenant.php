<?php

namespace App\Models\Traits;

use App\Services\Tenant\TenantScope;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder): void {
            if (TenantScope::getCompanyId()) {
                $builder->where('company_id', TenantScope::getCompanyId());
            }
        });

        static::creating(function ($model): void {
            if (TenantScope::getCompanyId() && !isset($model->company_id)) {
                $model->company_id = TenantScope::getCompanyId();
            }
        });

        static::updating(function ($model): void {
            if (TenantScope::getCompanyId() && isset($model->company_id)) {
                if ($model->company_id !== TenantScope::getCompanyId()) {
                    throw new \Exception('Tenant violation: Cannot change company_id');
                }
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(\App\Models\System\Company::class);
    }
}

