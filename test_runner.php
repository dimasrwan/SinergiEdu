<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $test = new Tests\Feature\OrangTua\ParentDashboardTest('test_parent_can_view_dashboard_with_owned_child_data');
    $reflection = new \ReflectionMethod($test, 'setUp');
    $reflection->setAccessible(true);
    $reflection->invoke($test);
    $test->test_parent_can_view_dashboard_with_owned_child_data();
    echo "OK";
} catch (\Throwable $e) {
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString();
}
