<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\School;
use App\Models\Role;

class SchoolInactiveHandlingTest extends TestCase
{
    use RefreshDatabase;

    protected $roleSuperAdmin;
    protected $roleAdmin;
    protected $rolePengawas;
    protected $roleGuru;
    protected $schoolA;
    protected $schoolB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->roleSuperAdmin = Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
        $this->roleAdmin = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        $this->rolePengawas = Role::firstOrCreate(['name' => 'pengawas'], ['display_name' => 'Pengawas']);
        $this->roleGuru = Role::firstOrCreate(['name' => 'guru'], ['display_name' => 'Guru']);

        $this->schoolA = School::create([
            'npsn' => '10000001',
            'name' => 'SMA Negeri 1 Harapan',
            'email' => 'sman1@harapan.sch.id',
            'is_active' => true,
        ]);

        $this->schoolB = School::create([
            'npsn' => '10000002',
            'name' => 'SMA Negeri 2 Bangsa',
            'email' => 'sman2@bangsa.sch.id',
            'is_active' => true,
        ]);
    }

    /**
     * Test 1: User dari sekolah aktif dapat login dan membuka dashboard.
     */
    public function test_active_school_user_can_login_and_access_dashboard(): void
    {
        $admin = User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Admin Sekolah Aktif',
            'email' => 'admin_aktif@harapan.sch.id',
            'password' => bcrypt('password'),
            'role_id' => $this->roleAdmin->id,
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin_aktif@harapan.sch.id',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect('/admin/dashboard');

        $dashboardResponse = $this->actingAs($admin)->get('/admin/dashboard');
        $dashboardResponse->assertStatus(200);
    }

    /**
     * Test 2 & 3: User dari sekolah nonaktif tidak dapat mengakses dashboard dan mendapatkan halaman khusus 403.
     */
    public function test_inactive_school_user_is_forbidden_with_custom_inactive_page(): void
    {
        $this->schoolA->update(['is_active' => false]);

        $guru = User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Guru Sekolah Nonaktif',
            'email' => 'guru@harapan.sch.id',
            'password' => bcrypt('password'),
            'role_id' => $this->roleGuru->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($guru)->get('/guru/dashboard');

        $response->assertStatus(403);
        $response->assertSee('Akses Sementara Tidak Tersedia');
        $response->assertSee('Sekolah Anda saat ini sedang dalam status nonaktif');
        $response->assertSee('Kembali ke Halaman Login');
        $response->assertDontSee('Your school is inactive');
    }

    /**
     * Test 4: Direct URL request ke rute apapun tetap ditolak jika sekolah nonaktif.
     */
    public function test_direct_url_access_is_blocked_for_inactive_school(): void
    {
        $this->schoolA->update(['is_active' => false]);

        $admin = User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Admin Test',
            'email' => 'admin_blocked@harapan.sch.id',
            'password' => bcrypt('password'),
            'role_id' => $this->roleAdmin->id,
            'is_active' => true,
        ]);

        $this->actingAs($admin)->get('/admin/teachers')->assertStatus(403);
        $this->actingAs($admin)->get('/admin/classes')->assertStatus(403);
        $this->actingAs($admin)->get('/admin/settings')->assertStatus(403);
    }

    /**
     * Test 5: User yang sudah login -> sekolah dinonaktifkan -> request berikutnya ditolak.
     */
    public function test_logged_in_user_is_blocked_immediately_when_school_is_deactivated(): void
    {
        $admin = User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Admin Live Test',
            'email' => 'admin_live@harapan.sch.id',
            'password' => bcrypt('password'),
            'role_id' => $this->roleAdmin->id,
            'is_active' => true,
        ]);

        // Request 1: Sekolah masih aktif
        $this->actingAs($admin)->get('/admin/dashboard')->assertStatus(200);

        // Super admin menonaktifkan sekolah A
        $this->schoolA->update(['is_active' => false]);

        // Request 2: Request berikutnya langsung ditolak
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(403);
        $response->assertSee('Akses Sementara Tidak Tersedia');
    }

    /**
     * Test 6: Sekolah A nonaktif tidak memengaruhi Sekolah B (Tenant Isolation).
     */
    public function test_inactive_school_a_does_not_affect_school_b(): void
    {
        $this->schoolA->update(['is_active' => false]);

        $adminB = User::create([
            'school_id' => $this->schoolB->id,
            'name' => 'Admin Sekolah B',
            'email' => 'admin_b@bangsa.sch.id',
            'password' => bcrypt('password'),
            'role_id' => $this->roleAdmin->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($adminB)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    /**
     * Test 7: Super Admin tetap memiliki akses platform penuh (school_id = null).
     */
    public function test_super_admin_can_access_platform_regardless_of_school_status(): void
    {
        $this->schoolA->update(['is_active' => false]);
        $this->schoolB->update(['is_active' => false]);

        $superAdmin = User::create([
            'school_id' => null,
            'name' => 'Platform Super Admin',
            'email' => 'superadmin@sinergiedu.id',
            'password' => bcrypt('password'),
            'role_id' => $this->roleSuperAdmin->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($superAdmin)->get('/super-admin/dashboard');
        $response->assertStatus(200);

        $schoolListResponse = $this->actingAs($superAdmin)->get('/super-admin/schools');
        $schoolListResponse->assertStatus(200);
    }

    /**
     * Test 8 & 9: Pengawas multi-sekolah: sekolah aktif bisa diakses, sekolah nonaktif ditolak dengan pesan jelas.
     */
    public function test_pengawas_can_access_active_schools_and_blocked_from_inactive_schools(): void
    {
        $pengawas = User::create([
            'school_id' => null,
            'name' => 'Drs. Pengawas Pembina',
            'email' => 'pengawas@dinas.go.id',
            'password' => bcrypt('password'),
            'role_id' => $this->rolePengawas->id,
            'is_active' => true,
        ]);

        // Assign kedua sekolah
        $pengawas->assignedSchools()->attach([$this->schoolA->id, $this->schoolB->id]);

        // Nonaktifkan Sekolah A
        $this->schoolA->update(['is_active' => false]);

        // 1. Pilih sekolah aktif (Sekolah B) -> Berhasil
        $responseB = $this->actingAs($pengawas)->post('/pengawas/select-school', [
            'school_id' => $this->schoolB->id,
        ]);
        $responseB->assertRedirect('/pengawas/dashboard');
        $this->actingAs($pengawas)->withSession(['pengawas_school_id' => $this->schoolB->id])
            ->get('/pengawas/dashboard')
            ->assertStatus(200);

        // 2. Coba set sekolah nonaktif (Sekolah A) -> Ditolak dan tetap di halaman pemilihan
        $responseA = $this->actingAs($pengawas)->post('/pengawas/select-school', [
            'school_id' => $this->schoolA->id,
        ]);
        $responseA->assertSessionHas('error');

        // 3. Jika session sengaja diarahkan ke sekolah nonaktif -> Redirected gracefully
        $scopeCheck = $this->actingAs($pengawas)->withSession(['pengawas_school_id' => $this->schoolA->id])
            ->get('/pengawas/dashboard');
        $scopeCheck->assertRedirect('/pengawas/select-school');
    }

    /**
     * Test 10: User dari sekolah nonaktif tetap dapat mengakses landing page (GET /) dengan status 200.
     */
    public function test_inactive_school_user_can_access_landing_page(): void
    {
        $this->schoolA->update(['is_active' => false]);

        $guru = User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Guru Sekolah Nonaktif',
            'email' => 'guru_landing@harapan.sch.id',
            'password' => bcrypt('password'),
            'role_id' => $this->roleGuru->id,
            'is_active' => true,
        ]);

        // Guest GET / -> 200
        $this->get('/')->assertStatus(200);

        // Authenticated inactive user GET / -> 200 (TIDAK 403 atau loop)
        $response = $this->actingAs($guru)->get('/');
        $response->assertStatus(200);
        $response->assertSee('SinergiEdu');
    }

    /**
     * Test 11: User dari sekolah nonaktif dapat mengakses GET /login dan melakukan POST /logout tanpa redirect loop.
     */
    public function test_inactive_school_user_can_access_login_page_and_logout(): void
    {
        $this->schoolA->update(['is_active' => false]);

        $guru = User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Guru Logout Test',
            'email' => 'guru_logout@harapan.sch.id',
            'password' => bcrypt('password'),
            'role_id' => $this->roleGuru->id,
            'is_active' => true,
        ]);

        // Guest GET /login -> 200
        $this->get('/login')->assertStatus(200);

        // Authenticated user can POST /logout successfully
        $response = $this->actingAs($guru)->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();

        // After logout, visiting /login is 200
        $this->get('/login')->assertStatus(200);
    }

    /**
     * Test 12: Google OAuth routes are not blocked by TenantMiddleware.
     */
    public function test_google_oauth_routes_are_accessible(): void
    {
        // GET /auth/google redirects to Google
        $response = $this->get('/auth/google');
        $this->assertTrue(in_array($response->status(), [302, 303]));
    }

    /**
     * Test 13: Password login test matrix (Active school + active user -> login OK).
     */
    public function test_password_login_active_school_and_active_user_succeeds(): void
    {
        $guru = User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Guru Aktif',
            'email' => 'guru_active_test@harapan.sch.id',
            'password' => bcrypt('password'),
            'role_id' => $this->roleGuru->id,
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'guru_active_test@harapan.sch.id',
            'password' => 'password',
        ]);

        $response->assertRedirect('/guru/dashboard');
        $this->assertAuthenticatedAs($guru);
    }

    /**
     * Test 14: Password login test matrix (Active school + inactive user -> login rejected).
     */
    public function test_password_login_active_school_and_inactive_user_is_rejected(): void
    {
        User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Guru Akun Inaktif',
            'email' => 'guru_inactive_account@harapan.sch.id',
            'password' => bcrypt('password'),
            'role_id' => $this->roleGuru->id,
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'guru_inactive_account@harapan.sch.id',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test 15: Password login test matrix (Inactive school + active user -> login rejected with 403 inactive school).
     */
    public function test_password_login_inactive_school_and_active_user_is_rejected(): void
    {
        $this->schoolA->update(['is_active' => false]);

        User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Guru Sekolah Nonaktif',
            'email' => 'guru_inactive_school@harapan.sch.id',
            'password' => bcrypt('password'),
            'role_id' => $this->roleGuru->id,
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'guru_inactive_school@harapan.sch.id',
            'password' => 'password',
        ]);

        $response->assertStatus(403);
        $this->assertGuest();
    }

    /**
     * Test 16: Password login test matrix (Inactive school + inactive user -> login rejected).
     */
    public function test_password_login_inactive_school_and_inactive_user_is_rejected(): void
    {
        $this->schoolA->update(['is_active' => false]);

        User::create([
            'school_id' => $this->schoolA->id,
            'name' => 'Guru Double Inaktif',
            'email' => 'guru_double_inactive@harapan.sch.id',
            'password' => bcrypt('password'),
            'role_id' => $this->roleGuru->id,
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'guru_double_inactive@harapan.sch.id',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test 17: Super Admin and Pengawas can login with password normally.
     */
    public function test_super_admin_and_pengawas_can_login_with_password(): void
    {
        $superAdmin = User::create([
            'school_id' => null,
            'name' => 'Platform SA',
            'email' => 'sa_login_test@sinergiedu.id',
            'password' => bcrypt('password'),
            'role_id' => $this->roleSuperAdmin->id,
            'is_active' => true,
        ]);

        $responseSA = $this->post('/login', [
            'email' => 'sa_login_test@sinergiedu.id',
            'password' => 'password',
        ]);
        $responseSA->assertRedirect('/super-admin/dashboard');
        $this->assertAuthenticatedAs($superAdmin);

        $this->post('/logout');
        $this->assertGuest();

        $pengawas = User::create([
            'school_id' => null,
            'name' => 'Pengawas Login Test',
            'email' => 'pengawas_login_test@dinas.go.id',
            'password' => bcrypt('password'),
            'role_id' => $this->rolePengawas->id,
            'is_active' => true,
        ]);

        $responsePengawas = $this->post('/login', [
            'email' => 'pengawas_login_test@dinas.go.id',
            'password' => 'password',
        ]);
        $responsePengawas->assertRedirect('/pengawas/dashboard');
        $this->assertAuthenticatedAs($pengawas);
    }
}
