<?php
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

$role = Role::firstOrCreate(['name' => 'pengawas'], ['display_name' => 'Pengawas']);
User::create([
    'name' => 'Pengawas Kedua', 
    'email' => 'pengawas2@sinergi.edu', 
    'password' => Hash::make('password'), 
    'role_id' => $role->id
]);
echo "Pengawas kedua dibuat.";
