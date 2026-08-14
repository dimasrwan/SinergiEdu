<?php

namespace App\Models\Scopes;

use App\Services\TenantService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $tenantService = app(TenantService::class);
        
        if ($tenantService->isTenantContext() && $tenantService->getSchoolId() !== null) {
            $builder->where($model->getTable() . '.school_id', $tenantService->getSchoolId());
        } elseif (auth()->check() && auth()->user()->school_id) {
            // Fallback for situations where TenantMiddleware hasn't run yet (e.g. Route Model Binding)
            $builder->where($model->getTable() . '.school_id', auth()->user()->school_id);
        }
    }
}
