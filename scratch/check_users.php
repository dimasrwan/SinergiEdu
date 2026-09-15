<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$users = DB::table('users')->get();
echo "Total Users: " . count($users) . "\n";
foreach ($users as $user) {
    echo "ID: {$user->id} | Email: {$user->email} | Role: " . ($user->role ?? $user->role_id ?? '-') . "\n";
}
