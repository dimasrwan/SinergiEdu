<?php
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

$role = Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);
$user = User::where('email', 'admin@sinergi.edu')->first();
if (!$user) {
    $user = User::create([
        'name' => 'Super Admin', 
        'email' => 'admin@sinergi.edu', 
        'password' => Hash::make('password'), 
        'role_id' => $role->id
    ]);
}
echo "Admin created: " . $user->email;
