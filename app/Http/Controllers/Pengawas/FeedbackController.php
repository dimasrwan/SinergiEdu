<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\PengawasFeedback;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Simpan feedback pengawas untuk siswa.
     */
    public function store(Request $request, Student $student): RedirectResponse
    {
        $data = $request->validate([
            'content' => 'required|string|min:10',
            'type'    => 'required|in:positive,negative,neutral',
        ], [
            'content.required' => 'Isi umpan balik wajib diisi.',
            'content.min'      => 'Umpan balik minimal 10 karakter.',
            'type.required'    => 'Jenis umpan balik wajib dipilih.',
        ]);

        PengawasFeedback::create([
            'pengawas_user_id'  => auth()->id(),
            'student_id'        => $student->id,
            'class_id'          => $request->class_id ?? null,
            'academic_year_id'  => $request->academic_year_id ?? null,
            'semester_id'       => $request->semester_id ?? null,
            'content'           => $data['content'],
            'type'              => $data['type'],
        ]);

        return redirect()->route('pengawas.students.show', $student)
            ->with('success', 'Umpan balik berhasil disimpan.');
    }

    /**
     * Hapus feedback pengawas.
     */
    public function destroy(PengawasFeedback $feedback): RedirectResponse
    {
        abort_if($feedback->pengawas_user_id !== auth()->id(), 403);

        $studentId = $feedback->student_id;
        $feedback->delete();

        return redirect()->route('pengawas.students.show', $studentId)
            ->with('success', 'Umpan balik berhasil dihapus.');
    }
}
