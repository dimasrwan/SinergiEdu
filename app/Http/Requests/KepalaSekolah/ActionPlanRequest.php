<?php

declare(strict_types=1);

namespace App\Http\Requests\KepalaSekolah;

use Illuminate\Foundation\Http\FormRequest;

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
            'target_user_id' => 'nullable|exists:users,id',
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
            'category.required' => 'Kategori wajib dipilih.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'due_date.after_or_equal' => 'Tanggal tenggat tidak boleh sebelum tanggal mulai.',
        ];
    }
}
