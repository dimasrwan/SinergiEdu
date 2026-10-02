<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\School;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherStoreAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;
    protected User $admin;
    protected AcademicYear $academicYear;
    protected Semester $semester;
    protected Subject $subject;
    protected Subject $subject2;
    protected Classroom $classroom;
    protected Classroom $classroom2;

    protected function setUp(): void
    {
        parent::setUp();

        $roleAdmin = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);

        $this->school = School::create([
            'npsn' => '9001',
            'name' => 'SMP Uji Coba',
            'email' => 'ujicoba@test.com',
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'school_id' => $this->school->id,
            'name' => 'Admin Uji Coba',
            'email' => 'admin.ujicoba@test.com',
            'password' => bcrypt('password'),
            'role_id' => $roleAdmin->id,
            'is_active' => true,
        ]);

        $this->academicYear = AcademicYear::create([
            'year' => '2025/2026',
            'school_id' => $this->school->id,
            'is_active' => true,
        ]);

        $this->semester = Semester::create([
            'name' => 'Ganjil',
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
            'is_active' => true,
        ]);

        $this->subject = Subject::create([
            'name' => 'Matematika',
            'code' => 'MTK',
            'school_id' => $this->school->id,
        ]);

        $this->subject2 = Subject::create([
            'name' => 'Bahasa Indonesia',
            'code' => 'BIN',
            'school_id' => $this->school->id,
        ]);

        $this->classroom = Classroom::create([
            'name' => 'Kelas VII-A',
            'grade_level' => 7,
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
        ]);

        $this->classroom2 = Classroom::create([
            'name' => 'Kelas VII-B',
            'grade_level' => 7,
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
        ]);
    }

    /**
     * Payload dasar form "Tambah Guru" tanpa penugasan.
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Guru Baru',
            'email' => 'guru.baru@test.com',
            'nip' => 'NIP-2026-001',
            'phone' => '081234567890',
            'address' => 'Jl. Pendidikan No. 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $overrides);
    }

    public function test_store_teacher_without_assignments(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.teachers.store'), $this->payload());

        $response->assertRedirect(route('admin.teachers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('users', 2); // admin + 1 guru baru
        $this->assertDatabaseCount('teachers', 1);
        $this->assertDatabaseCount('teacher_subjects', 0);
        $this->assertDatabaseHas('users', ['email' => 'guru.baru@test.com']);
    }

    public function test_store_teacher_with_one_assignment(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.teachers.store'), $this->payload([
            'assignments' => [
                ['class_id' => $this->classroom->id, 'subject_id' => $this->subject->id],
            ],
        ]));

        $response->assertRedirect(route('admin.teachers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('teachers', 1);
        $this->assertDatabaseCount('teacher_subjects', 1);

        $teacherId = \App\Models\Teacher::withoutGlobalScopes()->where('nip', 'NIP-2026-001')->first()->id;
        $this->assertDatabaseHas('teacher_subjects', [
            'teacher_id' => $teacherId,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);
    }

    public function test_store_teacher_with_multiple_assignments(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.teachers.store'), $this->payload([
            'assignments' => [
                ['class_id' => $this->classroom->id, 'subject_id' => $this->subject->id],
                ['class_id' => $this->classroom2->id, 'subject_id' => $this->subject2->id],
            ],
        ]));

        $response->assertRedirect(route('admin.teachers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('teachers', 1);
        $this->assertDatabaseCount('teacher_subjects', 2);
        $this->assertDatabaseHas('teacher_subjects', [
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);
        $this->assertDatabaseHas('teacher_subjects', [
            'class_id' => $this->classroom2->id,
            'subject_id' => $this->subject2->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);
    }

    /**
     * Regresi akar masalah: `:name` backtick di <x-select> (Blade component) dieksekusi
     * PHP sebagai shell_exec → name="" → browser tidak pernah mengirim `assignments`.
     * Form wajib merender binding Alpine literal pada input tersembunyi per baris.
     */
    public function test_form_renders_alpine_assignment_name_bindings(): void
    {
        $html = $this->actingAs($this->admin)
            ->get(route('admin.teachers.create'))
            ->assertOk()
            ->getContent();

        // Input tersembunyi pengirim penugasan memakai binding Alpine (bukan hasil eksekusi PHP).
        $this->assertStringContainsString(
            ':name="assignment.class_id ? `assignments[${index}][class_id]` : null"',
            $html
        );
        $this->assertStringContainsString(
            ':name="assignment.subject_id ? `assignments[${index}][subject_id]` : null"',
            $html
        );
        $this->assertStringContainsString('x-on:change="assignment.class_id = $event.target.value"', $html);
        $this->assertStringContainsString('x-on:change="assignment.subject_id = $event.target.value"', $html);
    }

    public function test_store_teacher_with_assignments_without_active_semester_fails(): void
    {
        // Tahun Ajaran aktif tapi Semester tidak aktif → penugasan tidak bisa disimpan,
        // seluruh proses dibatalkan (guru juga tidak dibuat), persis alur "+ Penugasan".
        $this->semester->update(['is_active' => false]);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.teachers.create'))
            ->post(route('admin.teachers.store'), $this->payload([
                'assignments' => [
                    ['class_id' => $this->classroom->id, 'subject_id' => $this->subject->id],
                ],
            ]));

        $response->assertRedirect(route('admin.teachers.create'));
        $response->assertSessionHas('error');

        $this->assertDatabaseCount('users', 1); // hanya admin
        $this->assertDatabaseCount('teachers', 0);
        $this->assertDatabaseCount('teacher_subjects', 0);
    }
}
