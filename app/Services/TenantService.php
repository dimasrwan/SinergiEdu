<?php

namespace App\Services;

use App\Models\School;

class TenantService
{
    protected ?int $schoolId = null;
    protected ?School $school = null;
    protected bool $tenantContext = false;

    /**
     * Initialize the tenant context for the current request.
     */
    public function setSchool(School $school): void
    {
        $this->schoolId = $school->id;
        $this->school = $school;
        $this->tenantContext = true;
    }

    /**
     * Get the current active school ID.
     */
    public function getSchoolId(): ?int
    {
        return $this->schoolId;
    }

    /**
     * Get the current active school instance.
     */
    public function getSchool(): ?School
    {
        return $this->school;
    }

    /**
     * Check if the application is currently running within a tenant context.
     */
    public function isTenantContext(): bool
    {
        return $this->tenantContext;
    }

    /**
     * Clear the tenant context (useful for testing or switching).
     */
    public function clear(): void
    {
        $this->schoolId = null;
        $this->school = null;
        $this->tenantContext = false;
    }
}
