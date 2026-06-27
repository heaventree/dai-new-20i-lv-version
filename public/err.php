<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';

// Fake the request as if visiting /
$_SERVER['REQUEST_URI']    = '/';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST']      = $_SERVER['HTTP_HOST'] ?? 'dai.ie';
$_SERVER['HTTPS']          = 'on';
$_SERVER['SERVER_PORT']    = '443';

ob_start();
try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    $request  = \Illuminate\Http\Request::capture();
    $response = $kernel->handle($request);

    $status = $response->getStatusCode();
    $output = ob_get_clean();

    // Send plain-text summary
    header('Content-Type: text/plain; charset=utf-8');
    echo "HTTP Status: $status\n";
    echo "Memory: " . round(memory_get_peak_usage(true)/1024/1024, 1) . " MB\n\n";

    if ($status >= 400) {
        echo "=== Response body (first 3000 chars) ===\n";
        echo substr(strip_tags($output), 0, 3000) . "\n";
    } else {
        echo "Request handled OK — status $status\n";
        echo "Response length: " . strlen($output) . " bytes\n";
        // Show first few lines of output to confirm it's HTML
        $lines = array_slice(explode("\n", $output), 0, 5);
        echo implode("\n", $lines) . "\n";
    }

} catch (\Throwable $e) {
    $output = ob_get_clean();
    header('Content-Type: text/plain; charset=utf-8');
    echo "EXCEPTION: " . get_class($e) . "\n";
    echo "Message:   " . $e->getMessage() . "\n";
    echo "File:      " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo $e->getTraceAsString() . "\n";
    if ($output) {
        echo "\n=== Partial output ===\n" . substr($output, 0, 1000) . "\n";
    }
}
