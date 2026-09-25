<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Material;
use App\Models\School;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentParent;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('q', ''));
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $currentUser = $request->user();
        if (!$currentUser) {
            return response()->json([], 401);
        }

        $tenantService = app(TenantService::class);
        $roleName = strtolower($currentUser->role->name ?? '');
        $schoolId = $tenantService->getSchoolId() ?? $currentUser->school_id;

        $rawResults = match ($roleName) {
            'super_admin', 'superadmin' => $this->searchAsSuperAdmin($query),
            'pengawas' => $this->searchAsPengawas($query, $currentUser, $schoolId),
            'admin' => $this->searchAsAdmin($query, $schoolId),
            'guru' => $this->searchAsGuru($query, $currentUser, $schoolId),
            'siswa' => $this->searchAsSiswa($query, $currentUser, $schoolId),
            'orangtua', 'orang tua' => $this->searchAsOrangTua($query, $currentUser, $schoolId),
            'waka', 'waka kurikulum' => $this->searchAsWaka($query, $schoolId),
            'kepala_sekolah', 'kepala sekolah' => $this->searchAsKepalaSekolah($query, $schoolId),
            'komite', 'komite sekolah' => $this->searchAsKomite($query),
            default => $this->searchAsDefaultTenant($query, $schoolId),
        };

        return response()->json($this->deduplicateResults($rawResults));
    }

    /**
     * Deduplicate search results by category and URL or title.
     */
    private function deduplicateResults(array $results): array
    {
        $seen = [];
        $unique = [];

        foreach ($results as $item) {
            $key = ($item['category'] ?? '') . '|' . ($item['url'] ?? $item['title'] ?? '');
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $unique[] = $item;
            }
        }

        return $unique;
    }

    /**
     * Platform-level search for Super Admin.
     */
    private function searchAsSuperAdmin(string $query): array
    {
        $results = [];

        // 1. Schools
        $schools = School::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('npsn', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%");
        })->limit(3)->get();

        foreach ($schools as $school) {
            $results[] = [
                'category' => 'Sekolah',
                'title' => $school->name,
                'subtitle' => 'NPSN: ' . $school->npsn . ($school->email ? ' • ' . $school->email : ''),
                'url' => route('super_admin.schools.show', $school->id),
            ];
        }

        // 2. Users platform-wide
        $users = User::with(['role', 'school'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(4)->get();

        foreach ($users as $user) {
            $roleDisplay = $user->role->display_name ?? ucfirst($user->role->name ?? 'Pengguna');
            $subtitle = $user->email;
            if ($user->school) {
                $subtitle .= ' • ' . $user->school->name;
            }
            $url = $user->school_id 
                ? route('super_admin.schools.show', $user->school_id)
                : route('super_admin.schools.index');

            $results[] = [
                'category' => 'Pengguna (' . $roleDisplay . ')',
                'title' => $user->name,
                'subtitle' => $subtitle,
                'url' => $url,
            ];
        }

        // 3. Classrooms platform-wide
        $classes = Classroom::with('school')
            ->where('name', 'LIKE', "%{$query}%")
            ->limit(2)->get();

        foreach ($classes as $cls) {
            $subtitle = 'Tingkat ' . $cls->grade_level . ' (' . $cls->education_level . ')';
            if ($cls->school) {
                $subtitle .= ' • ' . $cls->school->name;
            }
            $url = $cls->school_id 
                ? route('super_admin.schools.show', $cls->school_id)
                : route('super_admin.schools.index');

            $results[] = [
                'category' => 'Kelas',
                'title' => 'Kelas ' . $cls->name,
                'subtitle' => $subtitle,
                'url' => $url,
            ];
        }

        return $results;
    }

    /**
     * Multi-school search for Pengawas (scoped to active school or assigned schools).
     */
    private function searchAsPengawas(string $query, User $currentUser, ?int $schoolId): array
    {
        $assignedSchoolIds = $currentUser->assignedSchools()->pluck('schools.id')->toArray();
        $sessionSchoolId = session('pengawas_school_id') ? (int) session('pengawas_school_id') : null;

        // Security: active school must belong to assigned schools if assigned schools exist
        $activeSchoolId = null;
        if ($sessionSchoolId && in_array($sessionSchoolId, $assignedSchoolIds, true)) {
            $activeSchoolId = $sessionSchoolId;
        } elseif (!empty($assignedSchoolIds)) {
            $activeSchoolId = null; // search across all assigned schools
        } elseif ($schoolId && in_array($schoolId, $assignedSchoolIds, true)) {
            $activeSchoolId = $schoolId;
        } else {
            return [];
        }

        $results = [];

        // 1. Users in assigned/active schools
        $userQuery = User::with(['role', 'school'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            });

        if ($activeSchoolId) {
            $userQuery->where('school_id', $activeSchoolId);
        } else {
            $userQuery->whereIn('school_id', $assignedSchoolIds);
        }

        $users = $userQuery->limit(5)->get();

        foreach ($users as $user) {
            $roleDisplay = $user->role->display_name ?? ucfirst($user->role->name ?? 'Pengguna');
            $subtitle = $user->email;
            if ($user->school) {
                $subtitle .= ' • ' . $user->school->name;
            }

            $results[] = [
                'category' => 'Pengguna (' . $roleDisplay . ')',
                'title' => $user->name,
                'subtitle' => $subtitle,
                'url' => route('pengawas.users.index') . '?search=' . urlencode($user->name),
            ];
        }

        // 2. Classrooms in assigned/active schools
        $classQuery = Classroom::with('school')
            ->where('name', 'LIKE', "%{$query}%");

        if ($activeSchoolId) {
            $classQuery->where('school_id', $activeSchoolId);
        } else {
            $classQuery->whereIn('school_id', $assignedSchoolIds);
        }

        $classes = $classQuery->limit(3)->get();

        foreach ($classes as $cls) {
            $subtitle = 'Tingkat ' . $cls->grade_level . ' (' . $cls->education_level . ')';
            if ($cls->school) {
                $subtitle .= ' • ' . $cls->school->name;
            }

            $results[] = [
                'category' => 'Kelas',
                'title' => 'Kelas ' . $cls->name,
                'subtitle' => $subtitle,
                'url' => route('pengawas.students.index'),
            ];
        }

        return $results;
    }

    /**
     * Full tenant search for Admin Sekolah.
     */
    private function searchAsAdmin(string $query, ?int $schoolId): array
    {
        if (!$schoolId) {
            return [];
        }

        $results = [];

        // 1. Users in same school
        $users = User::with('role')
            ->where('school_id', $schoolId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(5)->get();

        foreach ($users as $user) {
            $userRole = strtolower($user->role->name ?? 'pengguna');
            $roleDisplayName = $user->role->display_name ?? ucfirst($userRole);

            $url = match ($userRole) {
                'guru' => route('admin.teachers.index') . '?search=' . urlencode($user->name),
                'siswa' => route('admin.students.index') . '?search=' . urlencode($user->name),
                'orangtua', 'orang tua' => route('admin.parents.index') . '?search=' . urlencode($user->name),
                'waka', 'waka kurikulum' => route('admin.wakas.index') . '?search=' . urlencode($user->name),
                'kepala_sekolah', 'kepala sekolah' => route('admin.kepala-sekolah.index') . '?search=' . urlencode($user->name),
                'pengawas' => route('admin.pengawas.index') . '?search=' . urlencode($user->name),
                'komite', 'komite sekolah' => route('admin.komite.index') . '?search=' . urlencode($user->name),
                default => route('admin.dashboard'),
            };

            $results[] = [
                'category' => 'Pengguna (' . $roleDisplayName . ')',
                'title' => $user->name,
                'subtitle' => $user->email,
                'url' => $url,
            ];
        }

        // 2. Classrooms in same school
        $classes = Classroom::where('school_id', $schoolId)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit(3)->get();

        foreach ($classes as $cls) {
            $results[] = [
                'category' => 'Kelas',
                'title' => 'Kelas ' . $cls->name,
                'subtitle' => 'Tingkat ' . $cls->grade_level . ' (' . $cls->education_level . ')',
                'url' => route('admin.classes.index') . '?search=' . urlencode($cls->name),
            ];
        }

        // 3. Subjects in same school
        $subjects = Subject::where('school_id', $schoolId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('code', 'LIKE', "%{$query}%");
            })
            ->limit(3)->get();

        foreach ($subjects as $sub) {
            $results[] = [
                'category' => 'Mata Pelajaran',
                'title' => $sub->name,
                'subtitle' => 'Kode: ' . $sub->code,
                'url' => route('admin.subjects.index') . '?search=' . urlencode($sub->name),
            ];
        }

        return $results;
    }

    /**
     * Resolve active academic context for the given school.
     */
    private function getActiveAcademicContext(?int $schoolId): array
    {
        if (!$schoolId) {
            return [null, null];
        }

        $activeYear = AcademicYear::where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();

        $activeSemester = $activeYear
            ? Semester::where('school_id', $schoolId)
                ->where('academic_year_id', $activeYear->id)
                ->where('is_active', true)
                ->first()
            : null;

        return [$activeYear, $activeSemester];
    }

    /**
     * Academic search for Guru (scoped to taught students, assignments, materials, classes, and subjects in current academic context).
     */
    private function searchAsGuru(string $query, User $currentUser, ?int $schoolId): array
    {
        if (!$schoolId) {
            return [];
        }

        $results = [];

        [$activeYear, $activeSemester] = $this->getActiveAcademicContext($schoolId);

        $teacher = Teacher::where('school_id', $schoolId)->where('user_id', $currentUser->id)->first();
        if (!$teacher) {
            return [];
        }

        $classIds = [];
        $subjectIds = [];
        $studentUserIds = [];

        // Active context is REQUIRED for active academic relationships
        if ($activeYear) {
            $teacherSubjectQuery = TeacherSubject::where('school_id', $schoolId)
                ->where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id);

            if ($activeSemester) {
                $teacherSubjectQuery->where(function ($q) use ($activeSemester) {
                    $q->where('semester_id', $activeSemester->id)
                      ->orWhereNull('semester_id');
                });
            }

            $classIds = $teacherSubjectQuery->pluck('class_id')->unique()->toArray();
            $subjectIds = $teacherSubjectQuery->pluck('subject_id')->unique()->toArray();

            if (!empty($classIds)) {
                $studentIds = StudentClass::where('school_id', $schoolId)
                    ->whereIn('class_id', $classIds)
                    ->where('academic_year_id', $activeYear->id)
                    ->pluck('student_id')
                    ->unique()
                    ->toArray();

                $studentUserIds = !empty($studentIds)
                    ? Student::where('school_id', $schoolId)->whereIn('id', $studentIds)->pluck('user_id')->unique()->toArray()
                    : [];
            }
        }

        // 1. Assignments created by this teacher in active context
        if ($activeYear) {
            $assignmentQuery = Assignment::where('teacher_id', $teacher->id)
                ->with(['classroom', 'subject'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                });

            if (!empty($classIds) && !empty($subjectIds)) {
                $assignmentQuery->whereIn('class_id', $classIds)->whereIn('subject_id', $subjectIds);
            }

            $assignments = $assignmentQuery->latest()->limit(3)->get();

            foreach ($assignments as $asn) {
                $subInfo = $asn->subject?->name ?? 'Tugas';
                $classInfo = $asn->classroom ? ' • Kelas ' . $asn->classroom->name : '';
                $results[] = [
                    'category' => 'Tugas',
                    'title' => $asn->title,
                    'subtitle' => $subInfo . $classInfo,
                    'url' => route('guru.assignments.show', $asn->id),
                ];
            }
        }

        // 2. Materials created by this teacher in active context
        if ($activeYear) {
            $materialQuery = Material::where('teacher_id', $teacher->id)
                ->with(['classroom', 'subject'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                });

            if (!empty($classIds) && !empty($subjectIds)) {
                $materialQuery->whereIn('class_id', $classIds)->whereIn('subject_id', $subjectIds);
            }

            $materials = $materialQuery->latest()->limit(3)->get();

            foreach ($materials as $mat) {
                $subInfo = $mat->subject?->name ?? 'Materi';
                $classInfo = $mat->classroom ? ' • Kelas ' . $mat->classroom->name : '';
                $results[] = [
                    'category' => 'Materi',
                    'title' => $mat->title,
                    'subtitle' => $subInfo . $classInfo,
                    'url' => route('guru.materials.index'),
                ];
            }
        }

        // 3. Students taught by this teacher in active context
        if (!empty($studentUserIds)) {
            $students = User::where('school_id', $schoolId)
                ->whereIn('id', $studentUserIds)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%");
                })
                ->limit(3)->get();

            foreach ($students as $stu) {
                $results[] = [
                    'category' => 'Siswa',
                    'title' => $stu->name,
                    'subtitle' => $stu->email,
                    'url' => route('guru.student-progress.index') . '?search=' . urlencode($stu->name),
                ];
            }
        }

        // 4. Teacher colleagues in same school
        $teachers = User::where('school_id', $schoolId)
            ->where('id', '!=', $currentUser->id)
            ->whereHas('role', fn($q) => $q->where('name', 'guru'))
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(2)->get();

        foreach ($teachers as $tch) {
            $results[] = [
                'category' => 'Rekan Guru',
                'title' => $tch->name,
                'subtitle' => $tch->email,
                'url' => route('guru.classes.index'),
            ];
        }

        // 5. Classrooms taught by this teacher in active context
        if (!empty($classIds)) {
            $classes = Classroom::where('school_id', $schoolId)
                ->whereIn('id', $classIds)
                ->where('name', 'LIKE', "%{$query}%")
                ->limit(2)->get();

            foreach ($classes as $cls) {
                $results[] = [
                    'category' => 'Kelas',
                    'title' => 'Kelas ' . $cls->name,
                    'subtitle' => 'Tingkat ' . $cls->grade_level . ' (' . $cls->education_level . ')',
                    'url' => route('guru.classes.index'),
                ];
            }
        }

        // 6. Subjects taught by this teacher in active context
        if (!empty($subjectIds)) {
            $subjects = Subject::where('school_id', $schoolId)
                ->whereIn('id', $subjectIds)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('code', 'LIKE', "%{$query}%");
                })
                ->limit(2)->get();

            foreach ($subjects as $sub) {
                $results[] = [
                    'category' => 'Mata Pelajaran',
                    'title' => $sub->name,
                    'subtitle' => 'Kode: ' . $sub->code,
                    'url' => route('guru.materials.index'),
                ];
            }
        }

        return $results;
    }

    /**
     * Learning search for Siswa (scoped to student's active assignments, materials, subjects, and teachers).
     */
    private function searchAsSiswa(string $query, User $currentUser, ?int $schoolId): array
    {
        if (!$schoolId) {
            return [];
        }

        $results = [];

        [$activeYear, $activeSemester] = $this->getActiveAcademicContext($schoolId);

        $student = Student::where('school_id', $schoolId)->where('user_id', $currentUser->id)->first();
        if (!$student) {
            return [];
        }

        $teacherUserIds = [];
        $subjectIds = [];
        $classIds = [];

        // Active context is REQUIRED for active class enrollments & assignments
        if ($activeYear) {
            $classIds = StudentClass::where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->where('academic_year_id', $activeYear->id)
                ->pluck('class_id')
                ->unique()
                ->toArray();

            if (!empty($classIds)) {
                $teacherSubjectQuery = TeacherSubject::where('school_id', $schoolId)
                    ->whereIn('class_id', $classIds)
                    ->where('academic_year_id', $activeYear->id);

                if ($activeSemester) {
                    $teacherSubjectQuery->where(function ($q) use ($activeSemester) {
                        $q->where('semester_id', $activeSemester->id)
                          ->orWhereNull('semester_id');
                    });
                }

                $teacherIds = $teacherSubjectQuery->pluck('teacher_id')->unique()->toArray();
                $teacherUserIds = !empty($teacherIds)
                    ? Teacher::where('school_id', $schoolId)->whereIn('id', $teacherIds)->pluck('user_id')->unique()->toArray()
                    : [];

                $subjectIds = $teacherSubjectQuery->pluck('subject_id')->unique()->toArray();
            }
        }

        // 1. Assignments for student's active classroom
        if (!empty($classIds)) {
            $assignments = Assignment::whereIn('class_id', $classIds)
                ->with(['subject', 'teacher.user'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->latest()
                ->limit(4)->get();

            foreach ($assignments as $asn) {
                $subInfo = $asn->subject?->name ?? 'Tugas';
                $teacherInfo = $asn->teacher?->user?->name ? ' • Guru: ' . $asn->teacher->user->name : '';
                $results[] = [
                    'category' => 'Tugas',
                    'title' => $asn->title,
                    'subtitle' => $subInfo . $teacherInfo,
                    'url' => route('siswa.assignments.show', $asn->id),
                ];
            }
        }

        // 2. Materials for student's active classroom
        if (!empty($classIds)) {
            $materials = Material::whereIn('class_id', $classIds)
                ->with(['subject', 'teacher.user'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->latest()
                ->limit(3)->get();

            foreach ($materials as $mat) {
                $subInfo = $mat->subject?->name ?? 'Materi';
                $teacherInfo = $mat->teacher?->user?->name ? ' • ' . $mat->teacher->user->name : '';
                $results[] = [
                    'category' => 'Materi',
                    'title' => $mat->title,
                    'subtitle' => $subInfo . $teacherInfo,
                    'url' => route('siswa.materials.show', $mat->id),
                ];
            }
        }

        // 3. Subjects of student's active class
        if (!empty($subjectIds)) {
            $subjects = Subject::where('school_id', $schoolId)
                ->whereIn('id', $subjectIds)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('code', 'LIKE', "%{$query}%");
                })
                ->limit(3)->get();

            foreach ($subjects as $sub) {
                $results[] = [
                    'category' => 'Mata Pelajaran',
                    'title' => $sub->name,
                    'subtitle' => 'Kode: ' . $sub->code,
                    'url' => route('siswa.materials.index'),
                ];
            }
        }

        // 4. Teachers (Guru Pengajar) teaching student's active class
        if (!empty($teacherUserIds)) {
            $teachers = User::where('school_id', $schoolId)
                ->whereIn('id', $teacherUserIds)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%");
                })
                ->limit(3)->get();

            foreach ($teachers as $tch) {
                $results[] = [
                    'category' => 'Guru Pengajar',
                    'title' => $tch->name,
                    'subtitle' => $tch->email,
                    'url' => route('siswa.materials.index'),
                ];
            }
        }

        return $results;
    }

    /**
     * Child & school search for Orang Tua / Wali (strictly scoped to parent's children in current academic context).
     */
    private function searchAsOrangTua(string $query, User $currentUser, ?int $schoolId): array
    {
        if (!$schoolId) {
            return [];
        }

        $results = [];

        [$activeYear, $activeSemester] = $this->getActiveAcademicContext($schoolId);

        $parent = StudentParent::where('school_id', $schoolId)->where('user_id', $currentUser->id)->first();
        $childStudents = $parent ? $parent->students()->where('school_id', $schoolId)->get() : collect();
        $childUserIds = $childStudents->pluck('user_id')->filter()->toArray();

        // 1. Strictly parent's OWN children (independent of academic year, child identity is permanent)
        if (!empty($childUserIds)) {
            $students = User::where('school_id', $schoolId)
                ->whereIn('id', $childUserIds)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%");
                })
                ->limit(3)->get();

            foreach ($students as $stu) {
                $results[] = [
                    'category' => 'Siswa / Anak',
                    'title' => $stu->name,
                    'subtitle' => $stu->email,
                    'url' => route('orangtua.progress.index'),
                ];
            }
        }

        // Academic context entities for children's active classes
        if ($activeYear && $childStudents->isNotEmpty()) {
            $childClassIds = StudentClass::where('school_id', $schoolId)
                ->whereIn('student_id', $childStudents->pluck('id'))
                ->where('academic_year_id', $activeYear->id)
                ->pluck('class_id')
                ->unique()
                ->toArray();

            if (!empty($childClassIds)) {
                // 2. Assignments for children's active classrooms
                $assignments = Assignment::whereIn('class_id', $childClassIds)
                    ->with(['subject', 'classroom'])
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%");
                    })
                    ->latest()
                    ->limit(3)->get();

                foreach ($assignments as $asn) {
                    $subInfo = $asn->subject?->name ?? 'Tugas';
                    $classInfo = $asn->classroom ? ' • Kelas ' . $asn->classroom->name : '';
                    $results[] = [
                        'category' => 'Tugas Anak',
                        'title' => $asn->title,
                        'subtitle' => $subInfo . $classInfo,
                        'url' => route('orangtua.assignments.index'),
                    ];
                }

                $teacherSubjectQuery = TeacherSubject::where('school_id', $schoolId)
                    ->whereIn('class_id', $childClassIds)
                    ->where('academic_year_id', $activeYear->id);

                if ($activeSemester) {
                    $teacherSubjectQuery->where(function ($q) use ($activeSemester) {
                        $q->where('semester_id', $activeSemester->id)
                          ->orWhereNull('semester_id');
                    });
                }

                $teacherIds = $teacherSubjectQuery->pluck('teacher_id')->unique()->toArray();
                $teacherUserIds = !empty($teacherIds)
                    ? Teacher::where('school_id', $schoolId)->whereIn('id', $teacherIds)->pluck('user_id')->unique()->toArray()
                    : [];

                // 3. Teachers teaching children's active class
                if (!empty($teacherUserIds)) {
                    $teachers = User::where('school_id', $schoolId)
                        ->whereIn('id', $teacherUserIds)
                        ->where(function ($q) use ($query) {
                            $q->where('name', 'LIKE', "%{$query}%")
                              ->orWhere('email', 'LIKE', "%{$query}%");
                        })
                        ->limit(2)->get();

                    foreach ($teachers as $tch) {
                        $results[] = [
                            'category' => 'Guru',
                            'title' => $tch->name,
                            'subtitle' => $tch->email,
                            'url' => route('orangtua.dashboard'),
                        ];
                    }
                }

                // 4. Subjects taught in children's active class
                $subjectIds = $teacherSubjectQuery->pluck('subject_id')->unique()->toArray();
                if (!empty($subjectIds)) {
                    $subjects = Subject::where('school_id', $schoolId)
                        ->whereIn('id', $subjectIds)
                        ->where(function ($q) use ($query) {
                            $q->where('name', 'LIKE', "%{$query}%")
                              ->orWhere('code', 'LIKE', "%{$query}%");
                        })
                        ->limit(2)->get();

                    foreach ($subjects as $sub) {
                        $results[] = [
                            'category' => 'Mata Pelajaran',
                            'title' => $sub->name,
                            'subtitle' => 'Kode: ' . $sub->code,
                            'url' => route('orangtua.grades.index'),
                        ];
                    }
                }
            }
        }

        return $results;
    }

    /**
     * Academic curriculum search for Waka Kurikulum.
     */
    private function searchAsWaka(string $query, ?int $schoolId): array
    {
        if (!$schoolId) {
            return [];
        }

        $results = [];

        [$activeYear] = $this->getActiveAcademicContext($schoolId);

        // 1. Assignments school-wide (monitoring)
        if ($activeYear) {
            $assignments = Assignment::whereHas('classroom', fn($q) => $q->where('school_id', $schoolId))
                ->with(['classroom', 'subject', 'teacher.user'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->latest()
                ->limit(3)->get();

            foreach ($assignments as $asn) {
                $subInfo = $asn->subject?->name ?? 'Tugas';
                $classInfo = $asn->classroom ? ' • Kelas ' . $asn->classroom->name : '';
                $results[] = [
                    'category' => 'Tugas (Monitoring)',
                    'title' => $asn->title,
                    'subtitle' => $subInfo . $classInfo,
                    'url' => route('waka.monitoring.assignments.show', $asn->id),
                ];
            }
        }

        // 2. Classes in same school
        $classes = Classroom::where('school_id', $schoolId)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit(3)->get();

        foreach ($classes as $cls) {
            $results[] = [
                'category' => 'Kelas',
                'title' => 'Kelas ' . $cls->name,
                'subtitle' => 'Tingkat ' . $cls->grade_level . ' (' . $cls->education_level . ')',
                'url' => route('waka.classes.index'),
            ];
        }

        // 3. Subjects in same school
        $subjects = Subject::where('school_id', $schoolId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('code', 'LIKE', "%{$query}%");
            })
            ->limit(3)->get();

        foreach ($subjects as $sub) {
            $results[] = [
                'category' => 'Mata Pelajaran',
                'title' => $sub->name,
                'subtitle' => 'Kode: ' . $sub->code,
                'url' => route('waka.subjects.index'),
            ];
        }

        // 4. Teachers in same school
        $teachers = User::where('school_id', $schoolId)
            ->whereHas('role', fn($q) => $q->where('name', 'guru'))
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(3)->get();

        foreach ($teachers as $tch) {
            $results[] = [
                'category' => 'Guru',
                'title' => $tch->name,
                'subtitle' => $tch->email,
                'url' => route('waka.monitoring.learning'),
            ];
        }

        // 5. Students in same school
        $students = User::where('school_id', $schoolId)
            ->whereHas('role', fn($q) => $q->where('name', 'siswa'))
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(3)->get();

        foreach ($students as $stu) {
            $results[] = [
                'category' => 'Siswa',
                'title' => $stu->name,
                'subtitle' => $stu->email,
                'url' => route('waka.monitoring.student-progress'),
            ];
        }

        return $results;
    }

    /**
     * Leadership and supervisory search for Kepala Sekolah.
     */
    private function searchAsKepalaSekolah(string $query, ?int $schoolId): array
    {
        if (!$schoolId) {
            return [];
        }

        $results = [];

        // 1. Teachers (Supervisi) in same school
        $teachers = User::where('school_id', $schoolId)
            ->whereHas('role', fn($q) => $q->where('name', 'guru'))
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(3)->get();

        foreach ($teachers as $tch) {
            $results[] = [
                'category' => 'Guru (Supervisi)',
                'title' => $tch->name,
                'subtitle' => $tch->email,
                'url' => route('kepala-sekolah.supervision.teacher-report'),
            ];
        }

        // 2. Students in same school
        $students = User::where('school_id', $schoolId)
            ->whereHas('role', fn($q) => $q->where('name', 'siswa'))
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(3)->get();

        foreach ($students as $stu) {
            $results[] = [
                'category' => 'Siswa (Akademik)',
                'title' => $stu->name,
                'subtitle' => $stu->email,
                'url' => route('kepala-sekolah.academic.perkembangan'),
            ];
        }

        // 3. Subjects in same school
        $subjects = Subject::where('school_id', $schoolId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('code', 'LIKE', "%{$query}%");
            })
            ->limit(2)->get();

        foreach ($subjects as $sub) {
            $results[] = [
                'category' => 'Mata Pelajaran',
                'title' => $sub->name,
                'subtitle' => 'Kode: ' . $sub->code,
                'url' => route('kepala-sekolah.academic.subjects'),
            ];
        }

        // 4. Classes in same school
        $classes = Classroom::where('school_id', $schoolId)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit(2)->get();

        foreach ($classes as $cls) {
            $results[] = [
                'category' => 'Kelas',
                'title' => 'Kelas ' . $cls->name,
                'subtitle' => 'Tingkat ' . $cls->grade_level . ' (' . $cls->education_level . ')',
                'url' => route('kepala-sekolah.academic.rekap'),
            ];
        }

        return $results;
    }

    /**
     * School programs and community search for Komite Sekolah.
     */
    private function searchAsKomite(string $query): array
    {
        $menus = [
            [
                'category' => 'Program Sekolah',
                'title' => 'Program & Kegiatan Sekolah',
                'subtitle' => 'Lihat program sekolah',
                'keywords' => ['program', 'kegiatan', 'agenda', 'rencana', 'kerja'],
                'url' => route('komite.school-programs'),
            ],
            [
                'category' => 'Aspirasi',
                'title' => 'Aspirasi Komite Sekolah',
                'subtitle' => 'Kelola aspirasi dan masukan sekolah',
                'keywords' => ['aspirasi', 'masukan', 'saran', 'komite', 'aduan', 'pesan'],
                'url' => route('komite.aspirations.index'),
            ],
            [
                'category' => 'Profil Sekolah',
                'title' => 'Profil & Informasi Sekolah',
                'subtitle' => 'Informasi umum dan identitas sekolah',
                'keywords' => ['profil', 'identitas', 'informasi', 'sekolah', 'tentang', 'kontak'],
                'url' => route('komite.school-profile'),
            ],
            [
                'category' => 'Capaian Kinerja',
                'title' => 'Ringkasan Kinerja Sekolah',
                'subtitle' => 'Laporan capaian dan performa sekolah',
                'keywords' => ['kinerja', 'capaian', 'performa', 'laporan', 'evaluasi', 'prestasi'],
                'url' => route('komite.performance-summary'),
            ],
        ];

        $lowerQuery = mb_strtolower($query);
        $results = [];

        foreach ($menus as $menu) {
            $matched = str_contains(mb_strtolower($menu['title']), $lowerQuery)
                || str_contains(mb_strtolower($menu['category']), $lowerQuery)
                || str_contains(mb_strtolower($menu['subtitle']), $lowerQuery)
                || collect($menu['keywords'])->contains(fn($k) => str_contains($k, $lowerQuery) || str_contains($lowerQuery, $k));

            if ($matched) {
                $results[] = [
                    'category' => $menu['category'],
                    'title' => $menu['title'],
                    'subtitle' => $menu['subtitle'],
                    'url' => $menu['url'],
                ];
            }
        }

        // If no specific menu matched, return top 2 relevant navigations
        if (empty($results)) {
            $results = array_slice(array_map(fn($m) => [
                'category' => $m['category'],
                'title' => $m['title'],
                'subtitle' => $m['subtitle'],
                'url' => $m['url'],
            ], $menus), 0, 2);
        }

        return $results;
    }

    /**
     * Default tenant search fallback.
     */
    private function searchAsDefaultTenant(string $query, ?int $schoolId): array
    {
        if (!$schoolId) {
            return [];
        }

        $results = [];

        $subjects = Subject::where('school_id', $schoolId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('code', 'LIKE', "%{$query}%");
            })
            ->limit(3)->get();

        foreach ($subjects as $sub) {
            $results[] = [
                'category' => 'Mata Pelajaran',
                'title' => $sub->name,
                'subtitle' => 'Kode: ' . $sub->code,
                'url' => '#',
            ];
        }

        return $results;
    }
}
