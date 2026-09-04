<?php

namespace Tests\Feature\WakaKurikulum;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Semester;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AcademicContextSafetyTest extends TestCase
{
    use RefreshDatabase;

    protected $schoolA;
    protected $schoolB;
    protected $wakaA;
    protected $wakaB;
    protected $unauthorizedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->schoolA = School::create(['name' => 'School A', 'npsn' => '111111', 'email' => 'a@a.com', 'is_active' => true]);
        $this->schoolB = School::create(['name' => 'School B', 'npsn' => '222222', 'email' => 'b@b.com', 'is_active' => true]);

        $wakaRole = Role::firstOrCreate(['id' => 3], ['name' => 'waka', 'display_name' => 'Waka']);
        $guruRole = Role::firstOrCreate(['id' => 4], ['name' => 'guru', 'display_name' => 'Guru']);

        $this->wakaA = User::factory()->create(['school_id' => $this->schoolA->id, 'role_id' => $wakaRole->id]);
        $this->wakaB = User::factory()->create(['school_id' => $this->schoolB->id, 'role_id' => $wakaRole->id]);
        
        $this->unauthorizedUser = User::factory()->create(['school_id' => $this->schoolA->id, 'role_id' => $guruRole->id]);
    }

    public function test_activating_new_academic_year_deactivates_old_year()
    {
        $oldYear = AcademicYear::create(['school_id' => $this->schoolA->id, 'year' => '2025/2026', 'is_active' => true]);
        $newYear = AcademicYear::create(['school_id' => $this->schoolA->id, 'year' => '2026/2027', 'is_active' => false]);

        $response = $this->actingAs($this->wakaA)->patch(route('waka.academic-years.toggle', $newYear));
        
        $response->assertRedirect()->assertSessionHas('success');

        $this->assertFalse($oldYear->fresh()->is_active);
        $this->assertTrue($newYear->fresh()->is_active);
        
        $this->assertEquals(1, AcademicYear::where('school_id', $this->schoolA->id)->where('is_active', true)->count());
    }

    public function test_activating_semester_from_active_year_succeeds()
    {
        $activeYear = AcademicYear::create(['school_id' => $this->schoolA->id, 'year' => '2026/2027', 'is_active' => true]);
        $semester = Semester::create(['school_id' => $this->schoolA->id, 'academic_year_id' => $activeYear->id, 'name' => 'Ganjil', 'is_active' => false]);

        $response = $this->actingAs($this->wakaA)->patch(route('waka.semesters.toggle', $semester));
        
        $response->assertRedirect()->assertSessionHas('success');
        $this->assertTrue($semester->fresh()->is_active);
        $this->assertEquals(1, Semester::where('school_id', $this->schoolA->id)->where('is_active', true)->count());
    }

    public function test_activating_semester_from_inactive_academic_year_is_rejected()
    {
        $inactiveYear = AcademicYear::create(['school_id' => $this->schoolA->id, 'year' => '2025/2026', 'is_active' => false]);
        $activeYear = AcademicYear::create(['school_id' => $this->schoolA->id, 'year' => '2026/2027', 'is_active' => true]);
        
        $invalidSemester = Semester::create(['school_id' => $this->schoolA->id, 'academic_year_id' => $inactiveYear->id, 'name' => 'Ganjil', 'is_active' => false]);

        $response = $this->actingAs($this->wakaA)->patch(route('waka.semesters.toggle', $invalidSemester));
        
        $response->assertRedirect()->assertSessionHas('error');
        $this->assertFalse($invalidSemester->fresh()->is_active);
    }

    public function test_school_a_switching_does_not_affect_school_b()
    {
        $yearA1 = AcademicYear::create(['school_id' => $this->schoolA->id, 'year' => '2025/2026', 'is_active' => true]);
        $yearA2 = AcademicYear::create(['school_id' => $this->schoolA->id, 'year' => '2026/2027', 'is_active' => false]);
        
        $yearB1 = AcademicYear::create(['school_id' => $this->schoolB->id, 'year' => '2027/2028', 'is_active' => true]);

        $this->actingAs($this->wakaA)->patch(route('waka.academic-years.toggle', $yearA2));

        $this->assertFalse($yearA1->fresh()->is_active);
        $this->assertTrue($yearA2->fresh()->is_active);
        
        $this->assertTrue($yearB1->fresh()->is_active);
    }

    public function test_unauthorized_role_cannot_switch_context()
    {
        $year = AcademicYear::create(['school_id' => $this->schoolA->id, 'year' => '2026/2027', 'is_active' => false]);

        $response = $this->actingAs($this->unauthorizedUser)->patch(route('waka.academic-years.toggle', $year));
        
        $response->assertForbidden();
        $this->assertFalse($year->fresh()->is_active);
    }
}
