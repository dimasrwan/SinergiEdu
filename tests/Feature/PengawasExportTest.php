<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\School;
use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PengawasExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $rolePengawasId = DB::table('roles')->where('name', 'pengawas')->value('id') ?? DB::table('roles')->insertGetId(['name' => 'pengawas', 'display_name' => 'Pengawas']);
        $roleAdminId = DB::table('roles')->where('name', 'admin')->value('id') ?? DB::table('roles')->insertGetId(['name' => 'admin', 'display_name' => 'Admin']);

        $this->rolePengawas = (object)['id' => $rolePengawasId];
        $this->roleAdmin = (object)['id' => $roleAdminId];

        $schoolAId = DB::table('schools')->insertGetId(['name' => 'School A']);
        $schoolBId = DB::table('schools')->insertGetId(['name' => 'School B']);
        
        $this->schoolA = (object)['id' => $schoolAId];
        $this->schoolB = (object)['id' => $schoolBId];

        $this->pengawas = User::forceCreate([
            'name' => 'Pengawas',
            'email' => 'pengawas@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->rolePengawas->id,
            'school_id' => $this->schoolA->id,
            'is_active' => true,
        ]);
        
        DB::table('pengawas_school')->insert([
            ['user_id' => $this->pengawas->id, 'school_id' => $this->schoolA->id],
        ]);
        
        $academicYearId = DB::table('academic_years')->insertGetId(['year' => '2024/2025', 'is_active' => true, 'school_id' => $this->schoolA->id]);
        $this->academicYear = (object)['id' => $academicYearId];
        
        $semesterId = DB::table('semesters')->insertGetId(['name' => 'Ganjil', 'is_active' => true, 'academic_year_id' => $this->academicYear->id, 'school_id' => $this->schoolA->id]);
        $this->semester = (object)['id' => $semesterId];

        $classAId = DB::table('classes')->insertGetId(['name' => 'Class A', 'school_id' => $this->schoolA->id, 'grade_level' => 1]);
        $this->classA = (object)['id' => $classAId];
        
        $studentAId = DB::table('students')->insertGetId(['nis' => '123', 'nisn' => '12345', 'school_id' => $this->schoolA->id, 'user_id' => $this->pengawas->id]);
        $this->studentA = clone $this->pengawas;
        $this->studentA->id = $studentAId; // Mocking student

        DB::table('student_classes')->insert([
            'student_id' => $studentAId,
            'class_id' => $this->classA->id,
            'academic_year_id' => $this->academicYear->id,
            'school_id' => $this->schoolA->id,
        ]);
    }

    public function test_pengawas_can_export_pdf_for_active_school()
    {
        $this->actingAs($this->pengawas)->withSession(['pengawas_school_id' => $this->schoolA->id]);

        $response = $this->get(route('pengawas.students.exportPdf'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_pengawas_can_export_excel_for_active_school()
    {
        $this->actingAs($this->pengawas)->withSession(['pengawas_school_id' => $this->schoolA->id]);

        $response = $this->get(route('pengawas.students.exportExcel'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_pengawas_cannot_export_unassigned_school()
    {
        // Try to access School B which is not assigned to Pengawas
        $this->actingAs($this->pengawas)->withSession(['pengawas_school_id' => $this->schoolB->id]);

        $response = $this->get(route('pengawas.students.exportPdf'));

        // Middleware should block it (either 403 or redirect depending on how 'pengawas.scope' works, usually 403 or redirect)
        // Actually, if it's not assigned, the middleware 'pengawas.scope' blocks it.
        $this->assertContains($response->status(), [302, 403, 404]);
    }
    
    public function test_csv_endpoint_is_removed()
    {
        $this->actingAs($this->pengawas)->withSession(['pengawas_school_id' => $this->schoolA->id]);
        
        // This route should not exist anymore
        $response = $this->get('/pengawas/students/download/report');
        $response->assertStatus(404);
    }
    
    public function test_guest_cannot_export()
    {
        $responsePdf = $this->get(route('pengawas.students.exportPdf'));
        $responsePdf->assertRedirect('/login');

        $responseExcel = $this->get(route('pengawas.students.exportExcel'));
        $responseExcel->assertRedirect('/login');
    }
    
    public function test_wrong_role_cannot_export()
    {
        $admin = User::factory()->create(['role_id' => $this->roleAdmin->id, 'school_id' => $this->schoolA->id]);
        
        $this->actingAs($admin);
        
        $responsePdf = $this->get(route('pengawas.students.exportPdf'));
        $responsePdf->assertStatus(403);
    }
}
