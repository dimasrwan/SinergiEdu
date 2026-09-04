<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'password', 'role_id', 'school_id', 'is_active', 'profile_photo_path'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use \App\Traits\TenantScoped;

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Relasi ke Role.
     */
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function profilePhotoUrl(): ?string
    {
        if ($this->profile_photo_path && Storage::disk('public')->exists($this->profile_photo_path)) {
            return asset('storage/' . $this->profile_photo_path);
        }
        return null;
    }

    public function school(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }

    protected static function booted()
    {
        $checkSchoolId = function ($user) {
            $roleName = is_string($user->role) ? $user->role : ($user->role->name ?? null);

            if ($roleName === 'super_admin' || $roleName === 'superadmin') {
                if ($user->school_id !== null) {
                    throw new \Exception('Super Admin must have school_id = NULL');
                }
            } else {
                if ($user->school_id === null) {
                    throw new \Exception('Normal user must have a valid school_id');
                }
            }
        };

        static::creating($checkSchoolId);
        static::updating($checkSchoolId);
    }
}
