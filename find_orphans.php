<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use Illuminate\Support\Facades\Storage;

$activeLogos = School::whereNotNull('logo')->pluck('logo')->toArray();
$allFiles = Storage::disk('public')->files('schools/logos');

$orphans = array_diff($allFiles, $activeLogos);

echo "Active Logos:\n";
print_r($activeLogos);

echo "\nAll Files in storage:\n";
print_r($allFiles);

echo "\nOrphans detected:\n";
print_r($orphans);
