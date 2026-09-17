<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Waka;
use App\Models\Pengawas;
use App\Models\KepalaSekolah;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Classroom;
use App\Models\StudentClass;
use App\Models\Subject;
use App\Models\TeacherSubject;
use App\Models\Material;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\StudentGrade;
use App\Models\Feedback;
use App\Models\ParentSupport;
use App\Services\TenantService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DevelopmentTestDataSeeder extends Seeder
{
    /**
     * Run the development seeder.
     */
    public function run(): void
    {
        if (!app()->environment('local', 'testing')) {
            $this->command->warn('DevelopmentTestDataSeeder should only be run in local/testing environment.');
            return;
        }

        $password = Hash::make('123');
        $tenantService = app(TenantService::class);

        // Fetch roles
        $roles = Role::pluck('id', 'name');

        // ==================================================
        // 4. SUPER ADMIN
        // ==================================================
        $tenantService->setPlatformContext();
        
        $superAdmin1 = User::create([
            'name' => 'Demo Super Admin 1',
            'email' => 'superadmin1@sinergiedu.test',
            'password' => $password,
            'role_id' => $roles['super_admin'],
            'school_id' => null,
            'is_active' => true,
        ]);

        $superAdmin2 = User::create([
            'name' => 'Demo Super Admin 2',
            'email' => 'superadmin2@sinergiedu.test',
            'password' => $password,
            'role_id' => $roles['super_admin'],
            'school_id' => null,
            'is_active' => true,
        ]);

        $tenantService->clear();

        // ==================================================
        // 3. SCHOOLS
        // ==================================================
        $schoolA = School::create([
            'name' => 'SMP Negeri 2 Banda Aceh',
            'npsn' => '10101010',
            'email' => 'smpn2@sinergiedu.test',
            'address' => 'Jl. Pendidikan No.2, Banda Aceh',
            'phone' => '0651100200',
            'is_active' => true,
        ]);

        $schoolB = School::create([
            'name' => 'SMA Negeri 3 Banda Aceh',
            'npsn' => '20202020',
            'email' => 'sman1@sinergiedu.test',
            'address' => 'Jl. Pelajar No.1, Banda Aceh',
            'phone' => '0651200300',
            'is_active' => true,
        ]);

        $this->seedSchoolData($schoolA, 'SMP', $password, $roles, $tenantService);
        $this->seedSchoolData($schoolB, 'SMA', $password, $roles, $tenantService);
        
        $tenantService->clear();
    }

    private function seedSchoolData(School $school, string $level, string $password, $roles, TenantService $tenantService)
    {
        $tenantService->setSchool($school);

        // ==================================================
        // 5 & 6. ADMIN
        // ==================================================
        $adminPrefix = strtolower($level) === 'smp' ? 'smp2' : 'sma3';
        $admin = User::create([
            'name' => 'Admin ' . $school->name,
            'email' => "admin.{$adminPrefix}@sinergiedu.test",
            'password' => $password,
            'role_id' => $roles['admin'],
            'school_id' => $school->id,
            'is_active' => true,
        ]);

        
        // ==================================================
        // NEW ROLES: Waka, Pengawas, Kepala Sekolah
        // ==================================================
        $wakaUser = User::create([
            'name' => 'Waka ' . $school->name,
            'email' => "waka.{$adminPrefix}@sinergiedu.test",
            'password' => $password,
            'role_id' => $roles['waka'],
            'school_id' => $school->id,
            'is_active' => true,
        ]);
        Waka::create([
            'school_id' => $school->id,
            'user_id' => $wakaUser->id,
            'nip' => '1980' . $school->id . 'W',
            'phone' => '0813' . rand(1000000, 9999999),
            'address' => 'Jl. Waka',
        ]);

        $pengawasUser = User::create([
            'name' => 'Pengawas ' . $school->name,
            'email' => "pengawas.{$adminPrefix}@sinergiedu.test",
            'password' => $password,
            'role_id' => $roles['pengawas'],
            'school_id' => $school->id,
            'is_active' => true,
        ]);
        Pengawas::create([
            'school_id' => $school->id,
            'user_id' => $pengawasUser->id,
            'nip' => '1980' . $school->id . 'P',
            'phone' => '0814' . rand(1000000, 9999999),
            'address' => 'Jl. Pengawas',
        ]);

        $kepsekUser = User::create([
            'name' => 'Kepala Sekolah ' . $school->name,
            'email' => "kepsek.{$adminPrefix}@sinergiedu.test",
            'password' => $password,
            'role_id' => $roles['kepala_sekolah'],
            'school_id' => $school->id,
            'is_active' => true,
        ]);
        KepalaSekolah::create([
            'school_id' => $school->id,
            'user_id' => $kepsekUser->id,
            'nip' => '1980' . $school->id . 'K',
            'phone' => '0815' . rand(1000000, 9999999),
            'address' => 'Jl. Kepsek',
        ]);

        // ==================================================
        // 7 & 8. GURU (Expanded to 4 teachers per school)
        // ==================================================
        $teacherUsers = [];
        $teachers = [];
        $teacherNames = $level === 'SMP' 
            ? ['Guru SMP 01 (Matematika & TIK)', 'Guru SMP 02 (IPA & B.Inggris)', 'Guru SMP 03 (B.Indonesia & PKN)', 'Guru SMP 04 (IPS & PAI)']
            : ['Guru SMA 01 (Matematika & Fisika)', 'Guru SMA 02 (B.Inggris & TIK)', 'Guru SMA 03 (Kimia & Biologi)', 'Guru SMA 04 (Ekonomi & Geografi)'];

        for ($i = 1; $i <= 4; $i++) {
            $user = User::create([
                'name' => $teacherNames[$i - 1],
                'email' => "guru.{$adminPrefix}.{$i}@sinergiedu.test",
                'password' => $password,
                'role_id' => $roles['guru'],
                'school_id' => $school->id,
                'is_active' => true,
            ]);
            $teacherUsers[] = $user;
            
            $teachers[] = Teacher::create([
                'school_id' => $school->id,
                'user_id' => $user->id,
                'nip' => '198001012010011' . $school->id . $i,
                'phone' => '0812' . rand(10000000, 99999999),
                'address' => "Jl. Guru {$level} {$i}",
            ]);
        }

        // ==================================================
        // 11 & 12. ORANG TUA (Expanded to 4 parents per school)
        // ==================================================
        $parentUsers = [];
        $parents = [];
        $parentNames = ['Bpk. Rahmad Hidayat', 'Ibu Nurhayati', 'Bpk. Hendra Wijaya', 'Ibu Cut Sarah'];

        for ($i = 1; $i <= 4; $i++) {
            $user = User::create([
                'name' => $parentNames[$i - 1] . " ({$level})",
                'email' => "ortu.{$adminPrefix}.{$i}@sinergiedu.test",
                'password' => $password,
                'role_id' => $roles['orangtua'],
                'school_id' => $school->id,
                'is_active' => true,
            ]);
            $parentUsers[] = $user;

            $parents[] = StudentParent::create([
                'school_id' => $school->id,
                'user_id' => $user->id,
                'phone' => '0852' . rand(10000000, 99999999),
                'address' => "Jl. Ortu {$level} {$i}",
            ]);
        }

        // ==================================================
        // 9 & 10. SISWA (Expanded to 8 students per school)
        // ==================================================
        $studentUsers = [];
        $students = [];
        $studentNames = [
            'Ahmad Fauzi', 'Siti Aminah', 'Rizky Pratama', 'Dian Lestari',
            'Bintang Ramadhan', 'Putri Ayu', 'Muhammad Aris', 'Zahra Amelia'
        ];

        for ($i = 1; $i <= 8; $i++) {
            $user = User::create([
                'name' => $studentNames[$i - 1] . " ({$level})",
                'email' => "siswa.{$adminPrefix}.{$i}@sinergiedu.test",
                'password' => $password,
                'role_id' => $roles['siswa'],
                'school_id' => $school->id,
                'is_active' => true,
            ]);
            $studentUsers[] = $user;

            // Relationships mapping:
            // Parent 1 -> Siswa 1 & 2
            // Parent 2 -> Siswa 3 & 4
            // Parent 3 -> Siswa 5 & 6
            // Parent 4 -> Siswa 7 & 8
            $parentId = $parents[(int) floor(($i - 1) / 2)]->id;

            $students[] = Student::create([
                'school_id' => $school->id,
                'user_id' => $user->id,
                'parent_id' => $parentId,
                'nis' => '1010' . $school->id . $i,
                'nisn' => '001234567' . $school->id . $i,
                'gender' => $i % 2 === 0 ? 'P' : 'L',
            ]);
        }

        // ==================================================
        // 13 & 14. ACADEMIC YEARS & SEMESTERS
        // ==================================================
        $yearStrOld = '2025/2026 (' . $school->name . ')';
        $yearStrNew = '2026/2027 (' . $school->name . ')';
        
        $yearOld = AcademicYear::firstOrCreate([
            'year' => $yearStrOld,
        ], [
            'school_id' => $school->id,
            'is_active' => false,
        ]);
        
        $semesterOldGanjil = Semester::firstOrCreate(['academic_year_id' => $yearOld->id, 'name' => 'Ganjil (' . $school->name . ')'], ['school_id' => $school->id, 'is_active' => false]);
        $semesterOldGenap = Semester::firstOrCreate(['academic_year_id' => $yearOld->id, 'name' => 'Genap (' . $school->name . ')'], ['school_id' => $school->id, 'is_active' => false]);

        $yearActive = AcademicYear::firstOrCreate([
            'year' => $yearStrNew,
        ], [
            'school_id' => $school->id,
            'is_active' => true,
        ]);
        
        $semesterActiveGanjil = Semester::firstOrCreate(['academic_year_id' => $yearActive->id, 'name' => 'Ganjil (' . $school->name . ')'], ['school_id' => $school->id, 'is_active' => true]);
        $semesterActiveGenap = Semester::firstOrCreate(['academic_year_id' => $yearActive->id, 'name' => 'Genap (' . $school->name . ')'], ['school_id' => $school->id, 'is_active' => false]);

        // ==================================================
        // 15 & 16. CLASSROOMS
        // ==================================================
        $classrooms = [];
        if ($level === 'SMP') {
            $classNames = [
                ['name' => 'VII A', 'grade' => '7'], ['name' => 'VII B', 'grade' => '7'],
                ['name' => 'VIII A', 'grade' => '8'], ['name' => 'VIII B', 'grade' => '8'],
                ['name' => 'IX A', 'grade' => '9'], ['name' => 'IX B', 'grade' => '9']
            ];
        } else {
            $classNames = [
                ['name' => 'X IPA 1', 'grade' => '10'], ['name' => 'X IPA 2', 'grade' => '10'],
                ['name' => 'XI IPA 1', 'grade' => '11'], ['name' => 'XI IPA 2', 'grade' => '11'],
                ['name' => 'XII IPA 1', 'grade' => '12'], ['name' => 'XII IPA 2', 'grade' => '12'],
                ['name' => 'X IPS 1', 'grade' => '10'], ['name' => 'X IPS 2', 'grade' => '10'],
                ['name' => 'XI IPS 1', 'grade' => '11'], ['name' => 'XI IPS 2', 'grade' => '11'],
                ['name' => 'XII IPS 1', 'grade' => '12'], ['name' => 'XII IPS 2', 'grade' => '12']
            ];
        }

        foreach ($classNames as $cl) {
            $classrooms[$cl['name']] = Classroom::create([
                'school_id' => $school->id,
                'education_level' => $level,
                'name' => $cl['name'],
                'grade_level' => $cl['grade'],
                'academic_year_id' => $yearActive->id,
                'homeroom_teacher_id' => $teachers[0]->id,
            ]);
        }

        // Historical Class
        $historicalClass = Classroom::create([
            'school_id' => $school->id,
            'education_level' => $level,
            'name' => 'Historical Class',
            'grade_level' => '10',
            'academic_year_id' => $yearOld->id,
            'homeroom_teacher_id' => null,
        ]);

        // ==================================================
        // 17. STUDENT CLASS PLACEMENT
        // ==================================================
        if ($level === 'SMP') {
            $placements = [
                0 => 'VII A', 1 => 'VII A',
                2 => 'VII B', 3 => 'VII B',
                4 => 'VIII A', 5 => 'VIII B',
                6 => 'IX A', 7 => 'IX B',
            ];
        } else {
            $placements = [
                0 => 'X IPA 1', 1 => 'X IPA 1',
                2 => 'X IPA 2', 3 => 'X IPA 2',
                4 => 'XI IPA 1', 5 => 'XI IPA 2',
                6 => 'XII IPA 1', 7 => 'XII IPS 1',
            ];
        }

        foreach ($placements as $index => $className) {
            StudentClass::create([
                'school_id' => $school->id,
                'student_id' => $students[$index]->id,
                'class_id' => $classrooms[$className]->id,
                'academic_year_id' => $yearActive->id,
            ]);
        }
        
        // Historical placement
        StudentClass::create([
            'school_id' => $school->id,
            'student_id' => $students[0]->id,
            'class_id' => $historicalClass->id,
            'academic_year_id' => $yearOld->id,
        ]);

        // ==================================================
        // 18 & 19. SUBJECTS
        // ==================================================
        $subjectNames = $level === 'SMP' 
            ? ['Bahasa Indonesia', 'Matematika', 'IPA', 'IPS', 'Bahasa Inggris', 'Pendidikan Agama Islam', 'PPKn', 'Informatika', 'PJOK', 'Seni Budaya']
            : ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Fisika', 'Kimia', 'Biologi', 'Sejarah', 'Geografi', 'Ekonomi', 'Sosiologi', 'Pendidikan Agama Islam', 'PPKn', 'Informatika', 'PJOK', 'Seni Budaya'];

        $subjects = [];
        $codeMap = [
            'Bahasa Indonesia' => 'BIND', 'Matematika' => 'MTK', 'IPA' => 'IPA', 'IPS' => 'IPS',
            'Bahasa Inggris' => 'BING', 'Pendidikan Agama Islam' => 'PAI', 'PPKn' => 'PKN',
            'Informatika' => 'TIK', 'PJOK' => 'PJK', 'Seni Budaya' => 'SNI', 'Fisika' => 'FIS',
            'Kimia' => 'KIM', 'Biologi' => 'BIO', 'Sejarah' => 'SJH', 'Geografi' => 'GEO',
            'Ekonomi' => 'EKO', 'Sosiologi' => 'SOS'
        ];
        
        foreach ($subjectNames as $sn) {
            $baseCode = $codeMap[$sn] ?? strtoupper(substr(str_replace(' ', '', $sn), 0, 4));
            $subjects[$sn] = Subject::create([
                'school_id' => $school->id,
                'name' => $sn,
                'code' => $baseCode . '-' . $school->id,
            ]);
        }

        // ==================================================
        // 20 & 21. TEACHER ASSIGNMENT (FOR BOTH GANJIL & GENAP)
        // ==================================================
        $teacherAssignments = [];
        if ($level === 'SMP') {
            $teacherAssignments = [
                0 => ['Matematika', 'Informatika'],
                1 => ['IPA', 'Bahasa Inggris'],
                2 => ['Bahasa Indonesia', 'PPKn'],
                3 => ['IPS', 'Pendidikan Agama Islam']
            ];
        } else {
            $teacherAssignments = [
                0 => ['Matematika', 'Fisika'],
                1 => ['Bahasa Inggris', 'Informatika'],
                2 => ['Kimia', 'Biologi'],
                3 => ['Ekonomi', 'Geografi']
            ];
        }

        // Map teacher assignments to ALL classrooms for both Ganjil (Active) & Genap
        foreach ($teacherAssignments as $teacherIdx => $subNames) {
            foreach ($subNames as $subName) {
                foreach ($classrooms as $className => $clsModel) {
                    // Create for active Semester Ganjil
                    TeacherSubject::create([
                        'school_id' => $school->id,
                        'teacher_id' => $teachers[$teacherIdx]->id,
                        'subject_id' => $subjects[$subName]->id,
                        'class_id' => $clsModel->id,
                        'academic_year_id' => $yearActive->id,
                        'semester_id' => $semesterActiveGanjil->id,
                    ]);
                    // Create for Semester Genap
                    TeacherSubject::create([
                        'school_id' => $school->id,
                        'teacher_id' => $teachers[$teacherIdx]->id,
                        'subject_id' => $subjects[$subName]->id,
                        'class_id' => $clsModel->id,
                        'academic_year_id' => $yearActive->id,
                        'semester_id' => $semesterActiveGenap->id,
                    ]);
                }
            }
        }

        // ==================================================
        // 22. MATERIALS
        // ==================================================
        if ($level === 'SMP') {
            $materialsData = [
                ['title' => 'Modul 1: Persamaan Linear & Aljabar', 'teacher_idx' => 0, 'subject' => 'Matematika'],
                ['title' => 'Modul 2: Berpikir Komputasional & Algoritma', 'teacher_idx' => 0, 'subject' => 'Informatika'],
                ['title' => 'Modul 1: Sistem Organ Manusia & Pernapasan', 'teacher_idx' => 1, 'subject' => 'IPA'],
                ['title' => 'Modul 2: Grammar & Descriptive Text', 'teacher_idx' => 1, 'subject' => 'Bahasa Inggris'],
                ['title' => 'Modul 1: Teks Laporan Hasil Observasi', 'teacher_idx' => 2, 'subject' => 'Bahasa Indonesia'],
                ['title' => 'Modul 1: Keragaman Budaya & Ekonomi', 'teacher_idx' => 3, 'subject' => 'IPS'],
            ];
        } else {
            $materialsData = [
                ['title' => 'Modul 1: Kalkulus & Integral Parsial', 'teacher_idx' => 0, 'subject' => 'Matematika'],
                ['title' => 'Modul 1: Hukum Newton & Dinamika Gerak', 'teacher_idx' => 0, 'subject' => 'Fisika'],
                ['title' => 'Modul 1: Analytical Exposition Essay', 'teacher_idx' => 1, 'subject' => 'Bahasa Inggris'],
                ['title' => 'Modul 1: Ikatan Kimia & Tata Nama Senyawa', 'teacher_idx' => 2, 'subject' => 'Kimia'],
            ];
        }

        foreach ($materialsData as $md) {
            Material::create([
                'teacher_id' => $teachers[$md['teacher_idx']]->id,
                'subject_id' => $subjects[$md['subject']]->id,
                'class_id' => $classrooms['VII A']?->id ?? $classrooms['X IPA 1']->id,
                'title' => $md['title'],
                'description' => 'Materi pembelajaran resmi untuk ' . $md['title'],
                'file_path' => null,
            ]);
        }

        // ==================================================
        // 23, 24, 25, 26. RICH ASSIGNMENTS & SUBMISSIONS (ALL STATES)
        // ==================================================
        $assignmentStatuses = [
            // Status A: Belum Dikerjakan (Deadline Masih Lama)
            [
                'title' => 'Tugas 1: Latihan Soal Persamaan Linear',
                'due' => now()->addDays(5),
                'sub' => 'none',
                'score' => null,
                'student_idx' => 0,
                'teacher_idx' => 0,
                'subject' => 'Matematika'
            ],
            // Status B: Belum Dikerjakan (Mendekati Deadline / Terlambat)
            [
                'title' => 'Tugas 2: Tugas Algoritma Dasar',
                'due' => now()->subDays(1),
                'sub' => 'none',
                'score' => null,
                'student_idx' => 0,
                'teacher_idx' => 0,
                'subject' => 'Informatika'
            ],
            // Status C: Sudah Dikerjakan tapi BELUM DINILAI
            [
                'title' => 'Tugas 3: Laporan Praktikum Organ Manusia',
                'due' => now()->addDays(2),
                'sub' => 'normal',
                'score' => null,
                'student_idx' => 0,
                'teacher_idx' => 1,
                'subject' => 'IPA'
            ],
            // Status D: Sudah Dikerjakan & SUDAH DINILAI (Sangat Baik)
            [
                'title' => 'Tugas 4: Writing Descriptive Text',
                'due' => now()->subDays(3),
                'sub' => 'normal',
                'score' => 95,
                'feedback' => 'Sangat mengagumkan! Tata bahasa dan kosa kata sangat tepat.',
                'student_idx' => 0,
                'teacher_idx' => 1,
                'subject' => 'Bahasa Inggris'
            ],
            // Status E: Sudah Dikerjakan & SUDAH DINILAI (Perlu Perbaikan)
            [
                'title' => 'Tugas 5: Analisis Teks Laporan Observasi',
                'due' => now()->subDays(4),
                'sub' => 'normal',
                'score' => 70,
                'feedback' => 'Penjelasan struktur teks masih kurang lengkap. Pelajari modul 1 kembali.',
                'student_idx' => 1,
                'teacher_idx' => 2,
                'subject' => 'Bahasa Indonesia'
            ],
            // Status F: Submit Terlambat + Sudah Dinilai
            [
                'title' => 'Tugas 6: Ringkasan Keragaman Budaya Indonesia',
                'due' => now()->subDays(5),
                'sub' => 'late',
                'score' => 80,
                'feedback' => 'Tugas bagus, namun ada pengurangan poin karena terlambat 1 hari.',
                'student_idx' => 1,
                'teacher_idx' => 3,
                'subject' => 'IPS'
            ],
            // Status G: Siswa 2 - Belum dikerjakan
            [
                'title' => 'Tugas 7: Latihan Soal Aljabar Lanjutan',
                'due' => now()->addDays(4),
                'sub' => 'none',
                'score' => null,
                'student_idx' => 1,
                'teacher_idx' => 0,
                'subject' => 'Matematika'
            ],
            // Status H: Siswa 3 - Sudah dikerjakan & Sudah Dinilai 88
            [
                'title' => 'Tugas 8: Projek Coding Sederhana',
                'due' => now()->subDays(2),
                'sub' => 'normal',
                'score' => 88,
                'feedback' => 'Logika pemrograman runtut dan rapi.',
                'student_idx' => 2,
                'teacher_idx' => 0,
                'subject' => 'Informatika'
            ]
        ];

        foreach ($assignmentStatuses as $asData) {
            $tIdx = $asData['teacher_idx'];
            $sName = isset($subjects[$asData['subject']]) ? $asData['subject'] : $teacherAssignments[$tIdx][0];
            $stIdx = $asData['student_idx'];
            $targetClass = $placements[$stIdx];

            $assignment = Assignment::create([
                'teacher_id' => $teachers[$tIdx]->id,
                'subject_id' => $subjects[$sName]->id,
                'class_id' => $classrooms[$targetClass]->id,
                'title' => $asData['title'],
                'description' => 'Instruksi tugas pembelajaran untuk ' . $asData['title'] . '. Harap dikerjakan dengan jujur dan teliti.',
                'deadline' => $asData['due'],
            ]);


            if ($asData['sub'] !== 'none') {
                $submission = AssignmentSubmission::create([
                    'assignment_id' => $assignment->id,
                    'student_id' => $students[$stIdx]->id,
                    'file_path' => 'submissions/jawaban_siswa_' . ($stIdx + 1) . '.pdf',
                    'notes' => 'Berikut adalah hasil pengerjaan tugas saya.',
                    'score' => $asData['score'],
                    'feedback' => $asData['feedback'] ?? null,
                    'created_at' => $asData['sub'] === 'late' ? $asData['due']->addHours(6) : $asData['due']->subHours(12),
                ]);

                if ($asData['score'] !== null) {
                    StudentGrade::updateOrCreate(
                        [
                            'student_id' => $students[$stIdx]->id,
                            'subject_id' => $subjects[$sName]->id,
                            'academic_year_id' => $yearActive->id,
                            'semester_id' => $semesterActiveGanjil->id,
                        ],
                        [
                            'class_id' => $classrooms[$targetClass]->id,
                            'teacher_id' => $teachers[$tIdx]->id,
                            'assignment_score' => $asData['score'],
                        ]
                    );
                }
            }
        }

        // ==================================================
        // 27. FEEDBACK AKADEMIK & SIKAP
        // ==================================================
        $secondSubName = isset($subjects['IPA']) ? 'IPA' : 'Bahasa Inggris';
        Feedback::create([
            'student_id' => $students[0]->id,
            'teacher_id' => $teachers[0]->id,
            'subject_id' => $subjects['Matematika']->id,
            'title' => 'Apresiasi Pemahaman Matematika',
            'type' => 'positive',
            'message' => 'Ahmad Fauzi menunjukkan perkembangan luar biasa pada materi aljabar.',
        ]);
        
        Feedback::create([
            'student_id' => $students[0]->id,
            'teacher_id' => $teachers[1]->id,
            'subject_id' => $subjects[$secondSubName]->id,
            'title' => 'Pengingat Ketepatan Waktu',
            'type' => 'negative',
            'message' => 'Mohon tingkatkan kedisiplinan mengumpulkan tugas tepat waktu.',
        ]);


        // ==================================================
        // 28. PARENT SUPPORT
        // ==================================================
        ParentSupport::create([
            'school_id' => $school->id,
            'student_id' => $students[0]->id,
            'academic_year_id' => $yearActive->id,
            'semester_id' => $semesterActiveGanjil->id,
            'week_number' => 1,
            'support_description' => 'Mendampingi anak belajar matematika di rumah setiap malam pukul 19.30.',
            'general_feedback' => 'Anak lebih fokus dan bersemangat.',
            'action_plan' => 'Membuat jadwal rutin belajar dan membatasi gadget.',
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(3),
        ]);

        // ==================================================
        // 29. HISTORICAL DATA (2025/2026)
        // ==================================================
        $historicalAssignment = Assignment::create([
            'teacher_id' => $teachers[0]->id,
            'subject_id' => $subjects[$subjectNames[0]]->id,
            'class_id' => $historicalClass->id,
            'title' => 'Tugas Portofolio Semester Lalu',
            'description' => 'Historical Assignment 2025/2026',
            'deadline' => now()->subMonths(8),
        ]);
        
        StudentGrade::create([
            'student_id' => $students[0]->id,
            'class_id' => $historicalClass->id,
            'subject_id' => $subjects[$subjectNames[0]]->id,
            'academic_year_id' => $yearOld->id,
            'semester_id' => $semesterOldGenap->id,
            'teacher_id' => $teachers[0]->id,
            'assignment_score' => 88,
        ]);
    }
}

