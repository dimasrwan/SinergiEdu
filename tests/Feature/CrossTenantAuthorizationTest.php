<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CrossTenantAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cross_tenant_assignment_access_denied()
    {
        $roleId = DB::table('roles')->where('name', 'guru')->value('id');
        if (!$roleId) {
            $roleId = DB::table('roles')->insertGetId(['name' => 'guru', 'display_name' => 'Guru']);
        }

        $schoolA = School::insertGetId(['name' => 'School A']);
        $schoolB = School::insertGetId(['name' => 'School B']);

        $userA = User::forceCreate(['name' => 'Guru A', 'email' => 'a@test.com', 'password' => bcrypt('pass'), 'role_id' => $roleId, 'school_id' => $schoolA, 'is_active' => true]);
        $teacherA = DB::table('teachers')->insertGetId(['user_id' => $userA->id, 'school_id' => $schoolA]);

        $userB = User::forceCreate(['name' => 'Guru B', 'email' => 'b@test.com', 'password' => bcrypt('pass'), 'role_id' => $roleId, 'school_id' => $schoolB, 'is_active' => true]);
        $teacherB = DB::table('teachers')->insertGetId(['user_id' => $userB->id, 'school_id' => $schoolB]);

        $classB = DB::table('classes')->insertGetId(['name' => 'Class B', 'school_id' => $schoolB, 'grade_level' => '10']);
        $subjectB = DB::table('subjects')->insertGetId(['name' => 'Subject B', 'school_id' => $schoolB, 'code' => 'SUBB']);

        $assignmentB = DB::table('assignments')->insertGetId([
            'title' => 'Tugas B',
            'description' => 'Desc',
            'teacher_id' => $teacherB,
            'class_id' => $classB,
            'subject_id' => $subjectB,
            'deadline' => now()->addDays(2),
        ]);

        $this->actingAs($userA);

        // Guru A tries to access Assignment B
        $response = $this->get('/guru/assignments/' . $assignmentB);
        $this->assertTrue(in_array($response->status(), [403, 404]));
    }
}
