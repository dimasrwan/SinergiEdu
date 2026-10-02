<?php

declare(strict_types=1);

namespace App\Http\Requests\KepalaSekolah;

use App\Models\SchoolActionPlan;
use App\Models\User;
use App\Services\RencanaAksiService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActionPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Mapping wajib: role pembuat => role target yang diizinkan (SchoolActionPlan::TARGET_MAP).
        $allowed = SchoolActionPlan::TARGET_MAP[auth()->user()?->role?->name] ?? [];
        $targetRoleUnion = array_values(array_unique(array_merge(...array_values(SchoolActionPlan::TARGET_MAP))));

        // School context aktif (TenantMiddleware/PengawasSchoolScope):
        // pengawas = sekolah terpilih dari session, bukan school_id profil.
        $schoolId = RencanaAksiService::activeSchoolId();

        return [
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'target_role' => [
                'nullable',
                Rule::in($targetRoleUnion),
                // Salah satu dari target_role / target_user_id wajib diisi.
                'required_without:target_user_id',
                function ($attribute, $value, $fail) use ($allowed) {
                    if ($value !== '' && ! in_array($value, $allowed, true)) {
                        $fail('Target Role tidak sesuai dengan aturan Rencana Aksi untuk role Anda.');
                    }
                },
            ],
            // Target Orang wajib berasal dari sekolah yang sama + role-nya harus cocok
            // dengan Target Role (jika dipilih) dan diizinkan untuk role pembuat.
            'target_user_id' => [
                'nullable',
                'integer',
                'required_without:target_role',
                Rule::exists('users', 'id')->where(function ($query) use ($schoolId) {
                    $query->where('school_id', $schoolId);
                }),
                function ($attribute, $value, $fail) {
                    $target = User::query()->find($value);

                    if (! $target) {
                        return;
                    }

                    $role = (string) $this->input('target_role');
                    if ($role !== '' && $target->role?->name !== $role) {
                        $fail('Target Orang harus memiliki role yang sama dengan Target Role.');
                    }
                },
                function ($attribute, $value, $fail) use ($allowed) {
                    $target = User::query()->find($value);

                    if (! $target) {
                        return;
                    }

                    if (! in_array($target->role?->name, $allowed, true)) {
                        $fail('Target Orang harus memiliki role: '.implode(', ', $allowed).' untuk role Anda.');
                    }
                },
            ],
            'category' => 'required|in:academic,character,memorization,operational',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'nullable|in:draft,in_progress,completed,cancelled',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul rencana aksi wajib diisi.',
            'target_role.in' => 'Target Role tidak valid.',
            'target_role.required_without' => 'Target Rencana Aksi wajib ditentukan: isi Target Role atau Target Orang.',
            'target_user_id.exists' => 'Target Orang harus berasal dari sekolah yang sama.',
            'target_user_id.integer' => 'Target Orang tidak valid.',
            'target_user_id.required_without' => 'Target Rencana Aksi wajib ditentukan: isi Target Role atau Target Orang.',
            'category.required' => 'Kategori wajib dipilih.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'due_date.after_or_equal' => 'Tanggal tenggat tidak boleh sebelum tanggal mulai.',
        ];
    }
}
