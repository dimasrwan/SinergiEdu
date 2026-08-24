<?php

namespace App\Traits;

use App\Models\Scopes\TenantScope;
use App\Services\TenantService;

trait TenantScoped
{
    /**
     * The "boot" method of the trait.
     *
     * @return void
     */
    protected static function bootTenantScoped()
    {
        // Add Global Scope to filter queries
        static::addGlobalScope(new TenantScope);

        // Automatically assign school_id when creating a new record
        static::creating(function ($model) {
            $tenantService = app(TenantService::class);
            if ($tenantService->isTenantContext() && $tenantService->getSchoolId() !== null) {
                // Prevent overriding if somehow manually set and we trust it (optional)
                // But for security, we force it to the current context
                $model->school_id = $tenantService->getSchoolId();
            }
        });
    }
}
