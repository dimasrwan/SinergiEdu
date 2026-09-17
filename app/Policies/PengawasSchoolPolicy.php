<?php

namespace App\Policies;

use App\Models\User;
use App\Models\School;

class PengawasSchoolPolicy
{
    public function view(User $user, School $school): bool
    {
        return $user->assignedSchools()->where('school_id', $school->id)->exists();
    }
}
