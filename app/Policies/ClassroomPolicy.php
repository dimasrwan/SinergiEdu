<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClassroomPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability, $model = null): bool|null
    {
        if ($user->role && $user->role->name === 'admin') {
            if ($model) {
                if (is_string($model)) {
                    return true;
                }
                $modelSchoolId = null;
                if ($model instanceof \App\Models\Teacher || $model instanceof \App\Models\Student || $model instanceof \App\Models\StudentParent) {
                    $relatedUser = \App\Models\User::find($model->user_id);
                    $modelSchoolId = $relatedUser ? $relatedUser->school_id : null;
                } else if (isset($model->school_id)) {
                    $modelSchoolId = $model->school_id;
                }
                
                if ($modelSchoolId === null || $user->school_id !== $modelSchoolId) {
                    return false;
                }
            }
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Classroom $classroom): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Classroom $classroom): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Classroom $classroom): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Classroom $classroom): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Classroom $classroom): bool
    {
        return false;
    }
}
