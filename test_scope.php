<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$adminA = \App\Models\User::where('email', 'admin_a@sinergiedu.test')->first();
$adminB = \App\Models\User::where('email', 'admin_b@sinergiedu.test')->first();

echo "Total Teachers Without Scope: " . \App\Models\Teacher::withoutGlobalScopes()->count() . "\n";

app(\App\Services\TenantService::class)->setSchool($adminA->school);
echo "Teacher Count Admin A: " . \App\Models\Teacher::count() . "\n";

app(\App\Services\TenantService::class)->setSchool($adminB->school);
echo "Teacher Count Admin B: " . \App\Models\Teacher::count() . "\n";

$newTeacher = \App\Models\Teacher::create([
    'user_id' => $adminB->id, // dummy
    'nip' => '9999',
    'phone' => '123',
    'address' => 'Test'
]);
echo "Created Teacher School ID: " . $newTeacher->school_id . "\n";
