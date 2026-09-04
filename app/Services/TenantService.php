<?php

namespace App\Services;

use App\Models\School;

class TenantService
{
    protected ?int $schoolId = null;
    protected ?School $school = null;
    protected bool $tenantContext = false;

    protected bool $platformContext = false;

    /**
     * Initialize the tenant context for the current request.
     */
    public function setSchool(School $school): void
    {
        $this->schoolId = $school->id;
        $this->school = $school;
        $this->tenantContext = true;
        $this->platformContext = false;
    }

    /**
     * Set the platform context (for Super Admin).
     */
    public function setPlatformContext(): void
    {
        $this->schoolId = null;
        $this->school = null;
        $this->tenantContext = false;
        $this->platformContext = true;
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
     * Check if the application is running in a platform context.
     */
    public function isPlatformContext(): bool
    {
        return $this->platformContext;
    }

    /**
     * Clear the tenant context (useful for testing or switching).
     */
    public function clear(): void
    {
        $this->schoolId = null;
        $this->school = null;
        $this->tenantContext = false;
        $this->platformContext = false;
    }
}
