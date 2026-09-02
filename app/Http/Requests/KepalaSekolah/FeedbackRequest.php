<?php

declare(strict_types=1);

namespace App\Http\Requests\KepalaSekolah;

use Illuminate\Foundation\Http\FormRequest;

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
            'recipient_id' => 'nullable|exists:users,id',
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
            'category.required' => 'Kategori wajib dipilih.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'title.required' => 'Judul feedback wajib diisi.',
            'message.required' => 'Isi feedback wajib diisi.',
        ];
    }
}
