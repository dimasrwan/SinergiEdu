<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Role;
use App\Models\SchoolActionPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Logika bersama fitur Rencana Aksi untuk role selain Kepala Sekolah.
 * Aturan target ada di SchoolActionPlan::TARGET_MAP (divalidasi ActionPlanRequest).
 */
class RencanaAksiService
{
    /**
     * Daftar role target yang diizinkan untuk role pembuat ($user).
     *
     * @return array<int, string>
     */
    public static function allowedTargetRoles(?User $user): array
    {
        return SchoolActionPlan::TARGET_MAP[$user?->role?->name] ?? [];
    }

    /**
     * Opsi <select> Target Role untuk role pembuat.
     *
     * @return array<int, array{value: string, label: string}>
     */
    public static function targetRoleOptions(array $allowedRoles): array
    {
        $labels = [
            'kepala_sekolah' => 'Kepala Sekolah',
            'waka' => 'Waka Kurikulum',
            'guru' => 'Guru',
            'siswa' => 'Siswa',
        ];

        return array_values(array_map(fn (string $role) => [
            'value' => $role,
            'label' => $labels[$role] ?? ucfirst($role),
        ], $allowedRoles));
    }

    /**
     * School context aktif untuk fitur Rencana Aksi.
     *
     * Sumber utama: TenantService (diisi TenantMiddleware tiap request).
     * Pengawas: sekolah terpilih dari session `pengawas_school_id` — TIDAK PERNAH
     * memakai school_id permanen profil Pengawas. Jika TenantService kosong
     * (scope Pengawas akan redirect), fallback aman = session sekolah terpilih.
     */
    public static function activeSchoolId(): ?int
    {
        $schoolId = app(TenantService::class)->getSchoolId();
        if ($schoolId !== null) {
            return $schoolId;
        }

        if (auth()->user()?->role?->name === 'pengawas') {
            $sessionSchoolId = session('pengawas_school_id');

            return $sessionSchoolId ? (int) $sessionSchoolId : null;
        }

        return auth()->user()?->school_id;
    }

    /**
     * Data form halaman Buat: user sekolah aktif yang role-nya diizinkan sebagai target.
     * (users di-exclude dari TenantScope, jadi batasi school_id via school context aktif.)
     *
     * @return array{groups: Collection, roleLabels: Collection, targetOptions: Collection, selectedTarget: string}
     */
    public static function createPayload(array $allowedRoles): array
    {
        $schoolId = self::activeSchoolId();

        $targets = User::query()
            ->where('school_id', $schoolId)
            ->whereHas('role')
            ->with('role')
            ->orderBy('name')
            ->get()
            ->filter(fn (User $u) => in_array($u->role->name, $allowedRoles, true))
            ->values();

        $roleLabels = Role::pluck('display_name', 'name');

        // Daftar untuk filter dinamis frontend saat Target Role berubah.
        $targetOptions = collect([
            ['value' => '', 'label' => '-- Semua sesuai role --', 'role' => null],
        ])->merge($targets->map(fn (User $u) => [
            'value' => (string) $u->id,
            'label' => $u->name,
            'role' => $u->role->name,
        ]))->values();

        // Target Orang hanya dirender setelah Target Role dipilih.
        // (UI: Target Orang menampilkan placeholder sampai Target Role dipilih —
        // daftar hasil intersection role target + school context aktif.)
        $activeRole = old('target_role') ?: null;
        $groups = $targets
            ->filter(fn (User $u) => $activeRole !== null && $u->role->name === $activeRole)
            ->groupBy(fn (User $u) => $u->role->name);

        // Old input target_user_id hanya dipertahankan jika masih valid terhadap daftar
        // yang dirender (Target Role lama + sekolah yang sama); selain itu di-reset.
        $visibleIds = $groups->flatten()->pluck('id')->map(static fn ($id) => (int) $id)->all();
        $oldTarget = old('target_user_id');
        $selectedTarget = $oldTarget !== null && in_array((int) $oldTarget, $visibleIds, true)
            ? (string) $oldTarget
            : '';

        return compact('groups', 'roleLabels', 'targetOptions', 'selectedTarget');
    }

    /**
     * Rencana aksi yang boleh dilihat $user (TenantScope tetap berlaku).
     * Siswa: hanya yang ditujukan kepadanya sendiri atau ke seluruh siswa (umum).
     * Role lain: yang dibuatnya, ditujukan kepadanya, atau ke role-nya (umum).
     */
    public static function visiblePlans(?User $user): Builder
    {
        $role = $user?->role?->name;

        return SchoolActionPlan::query()->where(function ($query) use ($user, $role) {
            if ($role === 'siswa') {
                $query->where('target_user_id', $user?->id)
                    ->orWhere(fn ($q) => $q->where('target_role', 'siswa')->whereNull('target_user_id'));

                return;
            }

            $query->where('user_id', $user?->id)
                ->orWhere('target_user_id', $user?->id)
                ->orWhere(fn ($q) => $q->where('target_role', $role)->whereNull('target_user_id'));
        });
    }

    /**
     * Simpan rencana aksi dari data request tervalidasi ('' disimpan sebagai null).
     */
    public static function storePlan(array $data): SchoolActionPlan
    {
        return SchoolActionPlan::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'target_role' => ($data['target_role'] ?? '') ?: null,
            'target_user_id' => ($data['target_user_id'] ?? '') ?: null,
            'category' => $data['category'],
            'priority' => $data['priority'],
            'status' => $data['status'] ?? 'draft',
            'start_date' => $data['start_date'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
