<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $schoolA = \App\Models\School::find(1);
    $schoolB = \App\Models\School::find(2);
    $adminA = \App\Models\User::where('email', 'admin_a@sinergiedu.test')->first();
    $adminB = \App\Models\User::where('email', 'admin_b@sinergiedu.test')->first();

    app(\App\Services\TenantService::class)->setSchool($schoolA);

    $teacherB = \App\Models\Teacher::withoutGlobalScopes()->create([
        'school_id' => $schoolB->id,
        'user_id' => $adminB->id,
        'nip' => '666',
        'phone' => '666',
        'address' => 'B'
    ]);

    $request = \Illuminate\Http\Request::create("/admin/teachers/{$teacherB->id}", 'PATCH', ['nip' => '999']);
    $request->setUserResolver(function () use ($adminA) {
        return $adminA;
    });

    $response = app()->handle($request);
    echo "Status: " . $response->getStatusCode() . "\n";
    echo "Content: " . $response->getContent() . "\n";
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
