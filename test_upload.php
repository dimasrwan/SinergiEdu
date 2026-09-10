<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$file = new Illuminate\Http\UploadedFile(__DIR__.'/public/favicon.ico', 'favicon.ico', 'image/x-icon', null, true);

$request = Illuminate\Http\Request::create(
    '/super-admin/schools', 'POST',
    ['name' => 'Test School', 'is_active' => true],
    [],
    ['logo' => $file]
);
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo substr($response->getContent(), 0, 500);
