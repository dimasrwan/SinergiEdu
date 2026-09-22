<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
try {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    echo 'BOOTSTRAP OK, STATUS: ' . $response->getStatusCode();
} catch (Throwable $e) {
    echo 'BOOTSTRAP EXCEPTION: ' . $e->getMessage() . '\n' . $e->getFile() . ':' . $e->getLine();
}
