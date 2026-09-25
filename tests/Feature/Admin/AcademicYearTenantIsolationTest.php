<?php

namespace Tests\Feature\Admin;

use App\Models\AcademicYear;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicYearTenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    private User $adminA;
    private User $adminB;
    private School $schoolA;
    private School $schoolB;
    private Role $roleAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        app(TenantService::class)->setPlatformContext();

        $this->roleAdmin = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin Sekolah']);

        $this->schoolA = School::create([
            'npsn' => '10000001',
            'name' => 'Sekolah A',
            'email' => 'sekolaha@test.com',
            'is_active' => true,
        ]);

        $this->schoolB = School::create([
            'npsn' => '20000002',
            'name' => 'Sekolah B',
            'email' => 'sekolahb@test.com',
            'is_active' => true,
        ]);

        $this->adminA = User::create([
            'role_id' => $this->roleAdmin->id,
            'school_id' => $this->schoolA->id,
            'name' => 'Admin Sekolah A',
            'email' => 'admin@sekolaha.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $this->adminB = User::create([
            'role_id' => $this->roleAdmin->id,
            'school_id' => $this->schoolB->id,
            'name' => 'Admin Sekolah B',
            'email' => 'admin@sekolahb.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }

    /**
     * TEST 1: School A membuat 2026/2027 -> PASS
     */
    public function test_school_a_can_create_2026_2027(): void
    {
        $response = $this->actingAs($this->adminA)
            ->post(route('admin.academic-years.store'), [
                'year' => '2026/2027',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.academic-years.index'));
        $response->assertSessionHasNoErrors();

        app(TenantService::class)->setPlatformContext();
        $this->assertTrue(
            AcademicYear::where('school_id', $this->schoolA->id)
                ->where('year', '2026/2027')
                ->exists()
        );
    }

    /**
     * TEST 2: School B membuat 2026/2027 (walaupun School A sudah punya) -> PASS
     */
    public function test_school_b_can_create_2026_2027_even_if_school_a_has_it(): void
    {
        app(TenantService::class)->setPlatformContext();
        AcademicYear::create([
            'school_id' => $this->schoolA->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminB)
            ->post(route('admin.academic-years.store'), [
                'year' => '2026/2027',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.academic-years.index'));
        $response->assertSessionHasNoErrors();

        app(TenantService::class)->setPlatformContext();
        $this->assertTrue(
            AcademicYear::where('school_id', $this->schoolB->id)
                ->where('year', '2026/2027')
                ->exists()
        );
    }

    /**
     * TEST 3: School A mencoba membuat 2026/2027 lagi -> FAIL validation duplicate
     */
    public function test_school_a_cannot_create_duplicate_year_in_same_school(): void
    {
        app(TenantService::class)->setPlatformContext();
        AcademicYear::create([
            'school_id' => $this->schoolA->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminA)
            ->post(route('admin.academic-years.store'), [
                'year' => '2026/2027',
                'is_active' => '1',
            ]);

        $response->assertSessionHasErrors(['year']);
    }

    /**
     * TEST 4: School B mencoba membuat 2026/2027 lagi -> FAIL validation duplicate
     */
    public function test_school_b_cannot_create_duplicate_year_in_same_school(): void
    {
        app(TenantService::class)->setPlatformContext();
        AcademicYear::create([
            'school_id' => $this->schoolB->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminB)
            ->post(route('admin.academic-years.store'), [
                'year' => '2026/2027',
                'is_active' => '1',
            ]);

        $response->assertSessionHasErrors(['year']);
    }

    /**
     * TEST 5: School A memiliki 2026/2027 active, School B memiliki 2026/2027 active -> Keduanya tetap active
     */
    public function test_both_schools_can_have_active_academic_year_concurrently(): void
    {
        $responseA = $this->actingAs($this->adminA)
            ->post(route('admin.academic-years.store'), [
                'year' => '2026/2027',
                'is_active' => '1',
            ]);
        $responseA->assertSessionHasNoErrors();

        $responseB = $this->actingAs($this->adminB)
            ->post(route('admin.academic-years.store'), [
                'year' => '2026/2027',
                'is_active' => '1',
            ]);
        $responseB->assertSessionHasNoErrors();

        $ayA = AcademicYear::withoutGlobalScopes()->where('school_id', $this->schoolA->id)->where('year', '2026/2027')->first();
        $ayB = AcademicYear::withoutGlobalScopes()->where('school_id', $this->schoolB->id)->where('year', '2026/2027')->first();

        $this->assertNotNull($ayA);
        $this->assertNotNull($ayB);
        $this->assertTrue((bool)$ayA->is_active);
        $this->assertTrue((bool)$ayB->is_active);
    }

    /**
     * TEST 6: School A membuat 2027/2028 active -> 2026/2027 School A menjadi inactive
     */
    public function test_creating_new_active_academic_year_deactivates_previous_in_same_school(): void
    {
        app(TenantService::class)->setPlatformContext();
        $ayA1 = AcademicYear::create([
            'school_id' => $this->schoolA->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $this->actingAs($this->adminA)
            ->post(route('admin.academic-years.store'), [
                'year' => '2027/2028',
                'is_active' => '1',
            ]);

        app(TenantService::class)->setPlatformContext();
        $ayA1->refresh();
        $ayA2 = AcademicYear::where('school_id', $this->schoolA->id)->where('year', '2027/2028')->first();

        $this->assertFalse((bool)$ayA1->is_active);
        $this->assertTrue((bool)$ayA2->is_active);
    }

    /**
     * TEST 7: Pastikan 2026/2027 School B TETAP active saat School A membuat active baru
     */
    public function test_school_a_activating_year_does_not_deactivate_school_b_year(): void
    {
        app(TenantService::class)->setPlatformContext();
        $ayA = AcademicYear::create([
            'school_id' => $this->schoolA->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $ayB = AcademicYear::create([
            'school_id' => $this->schoolB->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        // Admin A creates and activates 2027/2028
        $this->actingAs($this->adminA)
            ->post(route('admin.academic-years.store'), [
                'year' => '2027/2028',
                'is_active' => '1',
            ]);

        app(TenantService::class)->setPlatformContext();
        $ayB->refresh();
        $this->assertTrue((bool)$ayB->is_active, 'School B active academic year should remain active');
    }

    /**
     * TEST 8: Admin School A mencoba membuka Academic Year ID milik School B -> 403 / 404
     */
    public function test_admin_school_a_cannot_view_school_b_academic_year(): void
    {
        app(TenantService::class)->setPlatformContext();
        $ayB = AcademicYear::create([
            'school_id' => $this->schoolB->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminA)
            ->get(route('admin.academic-years.show', $ayB));

        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    /**
     * TEST 9: Admin School A mencoba update Academic Year milik School B -> ditolak (403 / 404)
     */
    public function test_admin_school_a_cannot_update_school_b_academic_year(): void
    {
        app(TenantService::class)->setPlatformContext();
        $ayB = AcademicYear::create([
            'school_id' => $this->schoolB->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminA)
            ->put(route('admin.academic-years.update', $ayB), [
                'year' => '2028/2029',
            ]);

        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    /**
     * TEST 10: Admin School A mencoba delete Academic Year milik School B -> ditolak (403 / 404)
     */
    public function test_admin_school_a_cannot_delete_school_b_academic_year(): void
    {
        app(TenantService::class)->setPlatformContext();
        $ayB = AcademicYear::create([
            'school_id' => $this->schoolB->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminA)
            ->delete(route('admin.academic-years.destroy', $ayB));

        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    /**
     * TEST 11: Search/filter Academic Year sebagai Admin School A -> hanya data School A
     */
    public function test_academic_year_list_only_returns_current_school_records(): void
    {
        app(TenantService::class)->setPlatformContext();
        $ayA = AcademicYear::create([
            'school_id' => $this->schoolA->id,
            'year' => '2025/2026',
            'is_active' => false,
        ]);

        $ayB = AcademicYear::create([
            'school_id' => $this->schoolB->id,
            'year' => '2026/2027',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminA)
            ->get(route('admin.academic-years.index'));

        $response->assertOk();
        $academicYears = $response->viewData('academicYears');
        $this->assertTrue($academicYears->pluck('id')->contains($ayA->id));
        $this->assertFalse($academicYears->pluck('id')->contains($ayB->id));
    }

    /**
     * TEST 12: Updating own academic year without changing year does not trigger duplicate error
     */
    public function test_admin_can_update_own_academic_year_without_duplicate_error(): void
    {
        app(TenantService::class)->setPlatformContext();
        $ayA = AcademicYear::create([
            'school_id' => $this->schoolA->id,
            'year' => '2026/2027',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->adminA)
            ->put(route('admin.academic-years.update', $ayA), [
                'year' => '2026/2027',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.academic-years.index'));
        $response->assertSessionHasNoErrors();

        app(TenantService::class)->setPlatformContext();
        $ayA->refresh();
        $this->assertTrue((bool)$ayA->is_active);
    }
}
