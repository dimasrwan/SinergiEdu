<?php

declare(strict_types=1);

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\View\View;

class MaterialController extends Controller
{
    use Concerns\HasStudentProfile;

    public function index(): View
    {
        $student = $this->requireStudentProfile();
        $classroom = $student->activeClassroom();

        $materials = collect();
        if ($classroom) {
            $materials = Material::where('class_id', $classroom->id)
                ->with(['teacher.user', 'subject'])
                ->latest()
                ->paginate(10);
        }

        return view('pages.siswa.materials.index', compact('materials', 'classroom'));
    }

    public function show(Material $material): View
    {
        $student = $this->requireStudentProfile();
        $classroom = $student->activeClassroom();

        // Pastikan materi ditujukan untuk kelas siswa tersebut
        abort_if(!$classroom || $material->class_id !== $classroom->id, 403, 'Anda tidak memiliki akses ke materi ini.');

        $material->load(['teacher.user', 'subject']);

        return view('pages.siswa.materials.show', compact('material'));
    }
}
