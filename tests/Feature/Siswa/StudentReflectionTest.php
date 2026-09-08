<?php

namespace Tests\Feature\Siswa;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\LearningMeeting;
use App\Models\Role;
use App\Models\School;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\StudentParent;
use App\Models\StudentReflection;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentReflectionTest extends TestCase
{
    use RefreshDatabase;

    private School $school;
    private AcademicYear $academicYear;
    private Semester $semester;
    private User $studentUser;
    private Student $student;
    private Classroom $classroom;
    private LearningMeeting $meeting;
    private User $parentUser;
    private StudentParent $parentProfile;
    private Teacher $teacher;
    private Subject $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Sekolah Refleksi',
            'npsn' => '99988877',
            'address' => 'Jl. Edu 1',
            'status' => 'active',
        ]);

        $this->academicYear = AcademicYear::create([
            'school_id' => $this->school->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $this->semester = Semester::create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
            'name' => 'Ganjil',
            'is_active' => true,
        ]);

        $parentRole = Role::firstOrCreate(['name' => 'orangtua', 'display_name' => 'Orang Tua']);
        $this->parentUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $parentRole->id]);
        $this->parentProfile = StudentParent::create(['school_id' => $this->school->id, 'user_id' => $this->parentUser->id]);

        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'display_name' => 'Siswa']);
        $this->studentUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $siswaRole->id]);
        $this->student = Student::create([
            'school_id' => $this->school->id,
            'user_id' => $this->studentUser->id,
            'parent_id' => $this->parentProfile->id,
            'nisn' => '1231231234',
        ]);

        $guruRole = Role::firstOrCreate(['name' => 'guru', 'display_name' => 'Guru']);
        $teacherUser = User::factory()->create(['school_id' => $this->school->id, 'role_id' => $guruRole->id]);
        $this->teacher = Teacher::create(['school_id' => $this->school->id, 'user_id' => $teacherUser->id]);
        $this->subject = Subject::create(['school_id' => $this->school->id, 'name' => 'IPA', 'code' => 'IPA']);

        $this->classroom = Classroom::create(['school_id' => $this->school->id, 'name' => 'Kelas 7A', 'grade_level' => 7]);
        $this->student->classes()->attach($this->classroom->id, ['school_id' => $this->school->id, 'academic_year_id' => $this->academicYear->id]);

        StudentGrade::create([
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);

        $this->meeting = LearningMeeting::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'meeting_number' => 1,
            'meeting_date' => now()->toDateString(),
            'topic' => 'Ekosistem',
        ]);
    }

    // 1. Student create own reflection
    public function test_student_can_create_own_reflection(): void
    {
        $response = $this->actingAs($this->studentUser)->post(route('siswa.reflections.store'), [
            'learning_meeting_id' => $this->meeting->id,
            'content' => 'Refleksi awal siswa.',
        ]);

        $response->assertRedirect(route('siswa.reflections.index'));
        $this->assertDatabaseHas('student_reflections', [
            'student_id' => $this->student->id,
            'learning_meeting_id' => $this->meeting->id,
            'content' => 'Refleksi awal siswa.',
        ]);
    }

    // 2. Student update own reflection & 3. Duplicate reflection prevented (Idempotent updateOrCreate)
    public function test_student_update_own_reflection_prevent_duplicates(): void
    {
        $this->actingAs($this->studentUser)->post(route('siswa.reflections.store'), [
            'learning_meeting_id' => $this->meeting->id,
            'content' => 'Refleksi awal.',
        ]);

        $this->actingAs($this->studentUser)->post(route('siswa.reflections.store'), [
            'learning_meeting_id' => $this->meeting->id,
            'content' => 'Refleksi diperbarui.',
        ]);

        $this->assertDatabaseCount('student_reflections', 1);
        $this->assertDatabaseHas('student_reflections', [
            'student_id' => $this->student->id,
            'learning_meeting_id' => $this->meeting->id,
            'content' => 'Refleksi diperbarui.',
        ]);
    }

    // 4. Forged student ID rejected (Authenticated user identity is source of truth)
    public function test_forged_student_id_is_ignored(): void
    {
        $otherStudentUser = User::factory()->create([
            'school_id' => $this->school->id,
            'role_id' => Role::where('name', 'siswa')->first()->id,
        ]);
        $otherStudent = Student::create([
            'school_id' => $this->school->id,
            'user_id' => $otherStudentUser->id,
            'nisn' => '9998887771',
        ]);

        $this->actingAs($this->studentUser)->post(route('siswa.reflections.store'), [
            'student_id' => $otherStudent->id, // Forged
            'learning_meeting_id' => $this->meeting->id,
            'content' => 'Test forged student ID',
        ]);

        // Reflection MUST be stored for authenticated student, NOT the forged student ID
        $this->assertDatabaseHas('student_reflections', [
            'student_id' => $this->student->id,
            'learning_meeting_id' => $this->meeting->id,
        ]);
        $this->assertDatabaseMissing('student_reflections', [
            'student_id' => $otherStudent->id,
        ]);
    }

    // 5. Unauthorized meeting rejected (Incompatible class context)
    public function test_unauthorized_meeting_is_rejected(): void
    {
        $otherClass = Classroom::create([
            'school_id' => $this->school->id,
            'name' => 'Kelas 8B',
            'grade_level' => 8,
        ]);
        $otherMeeting = LearningMeeting::create([
            'teacher_id' => $this->teacher->id,
            'class_id' => $otherClass->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
            'meeting_number' => 1,
            'meeting_date' => now()->toDateString(),
            'topic' => 'Topik Kelas Lain',
        ]);

        $response = $this->actingAs($this->studentUser)->post(route('siswa.reflections.store'), [
            'learning_meeting_id' => $otherMeeting->id,
            'content' => 'Mencoba akses meeting kelas lain.',
        ]);

        $response->assertStatus(403);
    }

    // 6. Cross-tenant meeting rejected
    public function test_cross_tenant_meeting_is_rejected(): void
    {
        $otherSchool = School::create(['name' => 'Sekolah Lain', 'npsn' => '11223344', 'status' => 'active']);
        $otherYear = AcademicYear::create(['school_id' => $otherSchool->id, 'year' => '2027/2028', 'is_active' => true]);
        $otherSemester = Semester::create(['school_id' => $otherSchool->id, 'academic_year_id' => $otherYear->id, 'name' => 'Ganjil', 'is_active' => true]);
        $otherTeacher = Teacher::create(['school_id' => $otherSchool->id, 'user_id' => User::factory()->create(['school_id' => $otherSchool->id])->id]);
        $otherSubject = Subject::create(['school_id' => $otherSchool->id, 'name' => 'Fisika', 'code' => 'FSK']);
        $otherClass = Classroom::create(['school_id' => $otherSchool->id, 'name' => 'Kelas 9A', 'grade_level' => 9]);

        $otherTenantMeeting = LearningMeeting::create([
            'teacher_id' => $otherTeacher->id,
            'class_id' => $otherClass->id,
            'subject_id' => $otherSubject->id,
            'academic_year_id' => $otherYear->id,
            'semester_id' => $otherSemester->id,
            'meeting_number' => 1,
            'meeting_date' => now()->toDateString(),
            'topic' => 'Cross tenant topic',
        ]);

        $response = $this->actingAs($this->studentUser)->post(route('siswa.reflections.store'), [
            'learning_meeting_id' => $otherTenantMeeting->id,
            'content' => 'Cross tenant injection test.',
        ]);

        $response->assertStatus(403);
    }

    // 7. Student cannot view another student's reflection on index page
    public function test_student_cannot_view_another_student_reflection(): void
    {
        $otherStudentUser = User::factory()->create([
            'school_id' => $this->school->id,
            'role_id' => Role::where('name', 'siswa')->first()->id,
        ]);
        $otherStudent = Student::create([
            'school_id' => $this->school->id,
            'user_id' => $otherStudentUser->id,
            'nisn' => '7778889990',
        ]);
        $otherStudent->classes()->attach($this->classroom->id, ['school_id' => $this->school->id, 'academic_year_id' => $this->academicYear->id]);

        StudentReflection::create([
            'student_id' => $otherStudent->id,
            'learning_meeting_id' => $this->meeting->id,
            'content' => 'Rahasia Siswa B',
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('siswa.reflections.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Rahasia Siswa B');
    }

    // 8. Parent can view child's reflection
    public function test_parent_can_view_child_reflection(): void
    {
        StudentReflection::create([
            'student_id' => $this->student->id,
            'learning_meeting_id' => $this->meeting->id,
            'content' => 'Refleksi anak untuk orang tua.',
        ]);

        $response = $this->actingAs($this->parentUser)->get(route('orangtua.progress.index'));
        $response->assertStatus(200);
        $response->assertSee('Refleksi Anak');
        $response->assertSee('Refleksi anak untuk orang tua.');
    }

    // 9. Parent cannot view unrelated student reflection
    public function test_parent_cannot_view_unrelated_student_reflection(): void
    {
        $unrelatedParentUser = User::factory()->create([
            'school_id' => $this->school->id,
            'role_id' => Role::where('name', 'orangtua')->first()->id,
        ]);
        $unrelatedParent = StudentParent::create(['school_id' => $this->school->id, 'user_id' => $unrelatedParentUser->id]);

        StudentReflection::create([
            'student_id' => $this->student->id,
            'learning_meeting_id' => $this->meeting->id,
            'content' => 'Refleksi rahasia anak.',
        ]);

        $response = $this->actingAs($unrelatedParentUser)->get(route('orangtua.progress.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Refleksi rahasia anak.');
    }

    // 10. Parent cannot mutate reflection (No POST/PUT/DELETE routes allowed)
    public function test_parent_cannot_mutate_reflection(): void
    {
        $response = $this->actingAs($this->parentUser)->post(route('siswa.reflections.store'), [
            'learning_meeting_id' => $this->meeting->id,
            'content' => 'Parent trying to edit',
        ]);

        // Student middleware rejects non-siswa users
        $response->assertStatus(403);
    }

    // 11. Teacher can view authorized student's reflection
    public function test_teacher_can_view_authorized_student_reflection(): void
    {
        \App\Models\TeacherSubject::create([
            'school_id' => $this->school->id,
            'teacher_id' => $this->teacher->id,
            'class_id' => $this->classroom->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester_id' => $this->semester->id,
        ]);

        StudentReflection::create([
            'student_id' => $this->student->id,
            'learning_meeting_id' => $this->meeting->id,
            'content' => 'Catatan refleksi untuk guru pengampu.',
        ]);

        $teacherUser = $this->teacher->user;
        $response = $this->actingAs($teacherUser)->get(route('guru.student-progress.show', [
            'student' => $this->student->id,
            'subject_id' => $this->subject->id,
            'class_id' => $this->classroom->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Refleksi Pembelajaran Siswa');
        $response->assertSee('Catatan refleksi untuk guru pengampu.');
    }

    // 12. Teacher cannot view unauthorized student's reflection
    public function test_teacher_cannot_view_unauthorized_student_reflection(): void
    {
        $unauthorizedTeacherUser = User::factory()->create([
            'school_id' => $this->school->id,
            'role_id' => Role::where('name', 'guru')->first()->id,
        ]);
        Teacher::create(['school_id' => $this->school->id, 'user_id' => $unauthorizedTeacherUser->id]);

        $response = $this->actingAs($unauthorizedTeacherUser)->get(route('guru.student-progress.show', [
            'student' => $this->student->id,
            'subject_id' => $this->subject->id,
            'class_id' => $this->classroom->id,
        ]));

        $response->assertStatus(403);
    }

    // 13. Validation: Empty or whitespace content is rejected
    public function test_empty_or_whitespace_reflection_content_is_rejected(): void
    {
        $response = $this->actingAs($this->studentUser)->post(route('siswa.reflections.store'), [
            'learning_meeting_id' => $this->meeting->id,
            'content' => '    ', // Only spaces
        ]);

        $response->assertSessionHasErrors(['content']);
    }
}
