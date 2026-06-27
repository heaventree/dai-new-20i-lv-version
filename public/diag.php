<?php
// DAI — server diagnostic. DELETE after use.
header('Content-Type: text/plain');

echo "=== PHP ===\n";
echo "Version: " . PHP_VERSION . "\n";
echo "SAPI: " . PHP_SAPI . "\n";

echo "\n=== .env ===\n";
$env = dirname(__DIR__) . '/.env';
echo "Exists: " . (file_exists($env) ? "YES" : "NO") . "\n";
if (file_exists($env)) {
    $lines = file($env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        // Mask secrets
        if (preg_match('/^(APP_KEY|DB_PASSWORD|STRIPE|MAIL_PASSWORD|GOOGLE_SERVICE)/i', $line)) {
            [$k] = explode('=', $line, 2);
            echo $k . "=***hidden***\n";
        } else {
            echo $line . "\n";
        }
    }
}

echo "\n=== Database ===\n";
// Load .env manually to get DB creds
$envVars = [];
if (file_exists($env)) {
    foreach (file($env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
        $envVars[trim($k)] = trim($v, '"\'');
    }
}
$host = $envVars['DB_HOST'] ?? '127.0.0.1';
$db   = $envVars['DB_DATABASE'] ?? '';
$user = $envVars['DB_USERNAME'] ?? '';
$pass = $envVars['DB_PASSWORD'] ?? '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    echo "Connection: OK\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(', ', $tables) . "\n";
    $rows = $pdo->query("SELECT COUNT(*) FROM settings")->fetchColumn();
    echo "Settings rows: $rows\n";
} catch (Exception $e) {
    echo "Connection FAILED: " . $e->getMessage() . "\n";
}

echo "\n=== Storage / Permissions ===\n";
$paths = [
    dirname(__DIR__) . '/storage/logs',
    dirname(__DIR__) . '/storage/framework/cache',
    dirname(__DIR__) . '/storage/framework/sessions',
    dirname(__DIR__) . '/storage/framework/views',
    dirname(__DIR__) . '/bootstrap/cache',
];
foreach ($paths as $path) {
    echo $path . ": " . (is_writable($path) ? "writable" : "NOT WRITABLE") . "\n";
}

echo "\n=== Latest Laravel log ===\n";
$logFile = dirname(__DIR__) . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = array_slice(file($logFile), -30);
    echo implode('', $lines);
} else {
    echo "No log file found.\n";
}
