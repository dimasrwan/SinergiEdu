<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$u = User::where('email', 'admin@sinergi.edu')->first();
if($u) {
    $u->password = Hash::make('password');
    $u->save();
    echo "Admin password reset.";
} else {
    echo "Admin not found.";
}
