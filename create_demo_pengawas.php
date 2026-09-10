<?php

use App\Models\Role;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\Hash;

$role = Role::firstOrCreate(['name' => 'pengawas'], ['display_name' => 'Pengawas']);
$user = User::create(['name' => 'Pengawas Demo', 'email' => 'pengawas@demo.com', 'password' => Hash::make('password'), 'role_id' => $role->id]);
$school1 = School::create(['name' => 'Sekolah Test 1', 'npsn' => '11111']);
$school2 = School::create(['name' => 'Sekolah Test 2', 'npsn' => '22222']);
$user->assignedSchools()->attach([$school1->id, $school2->id]);
