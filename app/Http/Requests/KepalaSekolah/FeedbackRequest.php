<?php

declare(strict_types=1);

namespace App\Http\Requests\KepalaSekolah;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recipient_role' => 'required|in:guru,waka,pengawas',
            // Penerima wajib berasal dari sekolah yang sama + role-nya harus cocok
            // dengan Tujuan (recipient_role) yang dipilih.
            'recipient_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('school_id', auth()->user()?->school_id);
                }),
                function ($attribute, $value, $fail) {
                    $recipient = User::query()->find($value);

                    if (! $recipient) {
                        return;
                    }

                    $role = (string) $this->input('recipient_role');
                    if ($role !== '' && $recipient->role?->name !== $role) {
                        $fail('Penerima harus memiliki role yang sama dengan Tujuan yang dipilih.');
                    }
                },
            ],
            'category' => 'required|in:strategic,academic,operational,recognition',
            'priority' => 'required|in:low,medium,high,urgent',
            'title' => 'required|string|max:150',
            'message' => 'required|string',
            'action_plan' => 'nullable|string',
            'action_deadline' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_role.required' => 'Penerima wajib dipilih.',
            'recipient_role.in' => 'Role penerima tidak valid.',
            'recipient_id.exists' => 'Penerima harus berasal dari sekolah yang sama.',
            'recipient_id.integer' => 'Penerima tidak valid.',
            'category.required' => 'Kategori wajib dipilih.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'title.required' => 'Judul feedback wajib diisi.',
            'message.required' => 'Isi feedback wajib diisi.',
        ];
    }
}
