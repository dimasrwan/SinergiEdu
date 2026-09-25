<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;

echo "APP_URL: " . env('APP_URL') . "\n";
echo "Storage public URL logic:\n";

$path = 'profile-photos/test.jpg';
echo "Path: " . $path . "\n";
echo "Storage::disk('public')->url(): " . Storage::disk('public')->url($path) . "\n";
echo "asset(): " . asset('storage/' . $path) . "\n";

