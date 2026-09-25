<?php
use App\Models\User;

$u = User::where('email', 'admin@sinergi.edu')->first();
if($u) {
    $u->email = 'superadmin@sinergi.edu';
    $u->save();
    echo "Email updated to: " . $u->email;
} else {
    echo "Admin not found.";
}
