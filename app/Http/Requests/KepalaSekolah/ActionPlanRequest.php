<?php

declare(strict_types=1);

namespace App\Http\Requests\KepalaSekolah;

use App\Models\User;
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
        return [
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'target_role' => 'nullable|in:guru,waka,pengawas',
            // Target Orang wajib berasal dari sekolah yang sama + role-nya harus cocok
            // dengan Target Role (target_role) yang dipilih (jika dipilih).
            'target_user_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('school_id', auth()->user()?->school_id);
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
            'target_user_id.exists' => 'Target Orang harus berasal dari sekolah yang sama.',
            'target_user_id.integer' => 'Target Orang tidak valid.',
            'category.required' => 'Kategori wajib dipilih.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'due_date.after_or_equal' => 'Tanggal tenggat tidak boleh sebelum tanggal mulai.',
        ];
    }
}
