<?php
require 'bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::whereHas('roles', function($q) {
    $q->where('name', 'pengawas');
})->first();

if ($user) {
    echo "Pengawas user: " . $user->email . " (Password: usually password123 or default)" . PHP_EOL;
} else {
    echo "No pengawas user found" . PHP_EOL;
    // Get first admin
    $admin = \App\Models\User::whereHas('roles', function($q) {
        $q->where('name', 'admin');
    })->first();
    if ($admin) {
        echo "Admin user: " . $admin->email . PHP_EOL;
    }
}
