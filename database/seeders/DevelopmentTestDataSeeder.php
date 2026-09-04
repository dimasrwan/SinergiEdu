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
        // 7 & 8. GURU

        // ==================================================
        $teacherUsers = [];
        $teachers = [];
        for ($i = 1; $i <= 2; $i++) {
            $user = User::create([
                'name' => "Guru {$level} 0{$i}",
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
        // 11 & 12. ORANG TUA
        // ==================================================
        $parentUsers = [];
        $parents = [];
        for ($i = 1; $i <= 2; $i++) {
            $user = User::create([
                'name' => "Orang Tua {$level} 0{$i}",
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
        // 9 & 10. SISWA
        // ==================================================
        $studentUsers = [];
        $students = [];
        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'name' => "Siswa {$level} 0{$i}",
                'email' => "siswa.{$adminPrefix}.{$i}@sinergiedu.test",
                'password' => $password,
                'role_id' => $roles['siswa'],
                'school_id' => $school->id,
                'is_active' => true,
            ]);
            $studentUsers[] = $user;

            // Relationships
            // Parent 1 -> Siswa 1 & 2
            // Parent 2 -> Siswa 3
            $parentId = ($i <= 2) ? $parents[0]->id : $parents[1]->id;

            $students[] = Student::create([
                'school_id' => $school->id,
                'user_id' => $user->id,
                'parent_id' => $parentId,
                'nis' => '1010' . $school->id . $i,
                'nisn' => '001234567' . $school->id . $i,
                'gender' => 'L',
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
        
        $semesterActiveGanjil = Semester::firstOrCreate(['academic_year_id' => $yearActive->id, 'name' => 'Ganjil (' . $school->name . ')'], ['school_id' => $school->id, 'is_active' => false]);
        $semesterActiveGenap = Semester::firstOrCreate(['academic_year_id' => $yearActive->id, 'name' => 'Genap (' . $school->name . ')'], ['school_id' => $school->id, 'is_active' => true]);

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
                'homeroom_teacher_id' => null,
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
                0 => 'VII A',
                1 => 'VIII A',
                2 => 'IX A',
            ];
        } else {
            $placements = [
                0 => 'X IPA 1',
                1 => 'XI IPA 1',
                2 => 'XII IPA 1',
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
            'Bahasa Indonesia' => 'BIND',
            'Matematika' => 'MTK',
            'IPA' => 'IPA',
            'IPS' => 'IPS',
            'Bahasa Inggris' => 'BING',
            'Pendidikan Agama Islam' => 'PAI',
            'PPKn' => 'PKN',
            'Informatika' => 'TIK',
            'PJOK' => 'PJK',
            'Seni Budaya' => 'SNI',
            'Fisika' => 'FIS',
            'Kimia' => 'KIM',
            'Biologi' => 'BIO',
            'Sejarah' => 'SJH',
            'Geografi' => 'GEO',
            'Ekonomi' => 'EKO',
            'Sosiologi' => 'SOS'
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
        // 20 & 21. TEACHER ASSIGNMENT
        // ==================================================
        $teacherAssignments = [];
        if ($level === 'SMP') {
            $teacherAssignments = [
                0 => ['Matematika', 'Informatika'],
                1 => ['IPA', 'Bahasa Inggris']
            ];
        } else {
            $teacherAssignments = [
                0 => ['Matematika', 'Fisika'],
                1 => ['Bahasa Inggris', 'Informatika']
            ];
        }

        foreach ($teacherAssignments as $teacherIdx => $subNames) {
            foreach ($subNames as $subName) {
                // Assign to the classes the students are in to make testing viable
                foreach ($placements as $className) {
                    TeacherSubject::create([
                        'school_id' => $school->id,
                        'teacher_id' => $teachers[$teacherIdx]->id,
                        'subject_id' => $subjects[$subName]->id,
                        'class_id' => $classrooms[$className]->id,
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
                ['title' => 'Modul Matematika — Persamaan Linear', 'teacher_idx' => 0, 'subject' => 'Matematika'],
                ['title' => 'Modul Informatika — Dasar Algoritma', 'teacher_idx' => 0, 'subject' => 'Informatika'],
                ['title' => 'Modul IPA — Sistem Pernapasan', 'teacher_idx' => 1, 'subject' => 'IPA'],
                ['title' => 'Modul Bahasa Inggris — Descriptive Text', 'teacher_idx' => 1, 'subject' => 'Bahasa Inggris'],
            ];
        } else {
            $materialsData = [
                ['title' => 'Modul Matematika — Integral', 'teacher_idx' => 0, 'subject' => 'Matematika'],
                ['title' => 'Modul Fisika — Gerak Lurus', 'teacher_idx' => 0, 'subject' => 'Fisika'],
                ['title' => 'Modul Bahasa Inggris — Analytical Exposition', 'teacher_idx' => 1, 'subject' => 'Bahasa Inggris'],
                ['title' => 'Modul Informatika — Algoritma Dasar', 'teacher_idx' => 1, 'subject' => 'Informatika'],
            ];
        }

        foreach ($materialsData as $md) {
            Material::create([
                'teacher_id' => $teachers[$md['teacher_idx']]->id,
                'subject_id' => $subjects[$md['subject']]->id,
                'class_id' => $classrooms[$placements[0]]->id, // assign to the first class
                'title' => $md['title'],
                'description' => 'Isi deskripsi dari ' . $md['title'],
                'file_path' => null, // don't leak physical storage
            ]);
        }

        // ==================================================
        // 23, 24, 25, 26. ASSIGNMENTS, SUBMISSIONS, GRADES, FEEDBACK
        // ==================================================
        $assignmentStatuses = [
            ['title' => 'Tugas 1: Normal + Score', 'due' => now()->addDays(7), 'sub' => 'normal', 'score' => 85, 'student_idx' => 0],
            ['title' => 'Tugas 2: Normal + Score NULL', 'due' => now()->addDays(7), 'sub' => 'normal', 'score' => null, 'student_idx' => 0],
            ['title' => 'Tugas 3: Terlambat + Score', 'due' => now()->subDays(2), 'sub' => 'late', 'score' => 78, 'student_idx' => 0],
            ['title' => 'Tugas 4: Terlambat + Score NULL', 'due' => now()->subDays(2), 'sub' => 'late', 'score' => null, 'student_idx' => 1],
            ['title' => 'Tugas 5: Belum Submit (Due Later)', 'due' => now()->addDays(2), 'sub' => 'none', 'score' => null, 'student_idx' => 1],
            ['title' => 'Tugas 6: Sudah Dinilai + Feedback', 'due' => now()->addDays(1), 'sub' => 'normal', 'score' => 92, 'student_idx' => 2],
        ];

        foreach ($assignmentStatuses as $index => $asData) {
            // Assign sequentially across teachers for variety
            $teacherIdx = $index % 2;
            $subjectName = $teacherAssignments[$teacherIdx][0]; // Pick first subject of teacher
            $targetClass = $placements[$asData['student_idx']];

            $assignment = Assignment::create([
                'teacher_id' => $teachers[$teacherIdx]->id,
                'subject_id' => $subjects[$subjectName]->id,
                'class_id' => $classrooms[$targetClass]->id,
                'title' => $asData['title'],
                'description' => 'Instruksi untuk ' . $asData['title'],
                'deadline' => $asData['due'],
            ]);

            if ($asData['sub'] !== 'none') {
                $submitTime = $asData['sub'] === 'late' ? now()->subDay(1) : now()->subDays(3);
                
                $submission = AssignmentSubmission::create([
                    'assignment_id' => $assignment->id,
                    'student_id' => $students[$asData['student_idx']]->id,
                    'file_path' => 'submissions/dummy.pdf',
                    'notes' => 'Ini jawaban saya.',
                    'score' => $asData['score'],
                    'feedback' => $asData['score'] === 92 ? 'Kerja bagus, sangat teliti!' : null,
                ]);

                if ($asData['score'] !== null) {
                    StudentGrade::updateOrCreate(
                        [
                            'student_id' => $students[$asData['student_idx']]->id,
                            'subject_id' => $subjects[$subjectName]->id,
                            'academic_year_id' => $yearActive->id,
                            'semester_id' => $semesterActiveGenap->id,
                        ],
                        [
                            'class_id' => $classrooms[$targetClass]->id,
                            'teacher_id' => $teachers[$teacherIdx]->id,
                            'assignment_score' => $asData['score'],
                        ]
                    );
                }
            }
        }

        // ==================================================
        // 26. FEEDBACK UMUM
        // ==================================================
        Feedback::create([
            'student_id' => $students[0]->id,
            'teacher_id' => $teachers[0]->id,
            'subject_id' => $subjects[$subjectNames[0]]->id,
            'title' => 'Evaluasi Sikap Belajar',
            'type' => 'positive',
            'message' => 'Menunjukkan pemahaman konsep yang baik, namun partisipasi masih kurang.',
        ]);
        
        Feedback::create([
            'student_id' => $students[1]->id,
            'teacher_id' => $teachers[1]->id,
            'subject_id' => $subjects[$subjectNames[0]]->id,
            'title' => 'Catatan Tambahan',
            'type' => 'negative',
            'message' => 'Perlu meningkatkan ketelitian dalam menyelesaikan soal.',
        ]);

        // ==================================================
        // 27. PARENT SUPPORT
        // ==================================================
        ParentSupport::create([
            'school_id' => $school->id,
            'student_id' => $students[0]->id,
            'academic_year_id' => $yearActive->id,
            'semester_id' => $semesterActiveGenap->id,
            'week_number' => 1,
            'support_description' => 'Anak terlihat antusias belajar matematika.',
            'general_feedback' => 'Sehat dan aktif.',
            'action_plan' => 'Lanjutkan tingkat belajar.',
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(5),
        ]);

        // ==================================================
        // 28. HISTORICAL DATA (2025/2026)
        // ==================================================
        $historicalAssignment = Assignment::create([
            'teacher_id' => $teachers[0]->id,
            'subject_id' => $subjects[$subjectNames[0]]->id,
            'class_id' => $historicalClass->id,
            'title' => 'Tugas Masa Lalu',
            'description' => 'Historical Assignment',
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
