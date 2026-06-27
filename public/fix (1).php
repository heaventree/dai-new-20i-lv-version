<?php
// DAI — cache cleaner. DELETE after use.
header('Content-Type: text/plain; charset=utf-8');

$base = dirname(__DIR__);

echo "=== Bootstrap cache ===\n";
$cacheDir = $base . '/bootstrap/cache';
$files = glob($cacheDir . '/*.php') ?: [];
if (!$files) {
    echo "No cached files found (already clean)\n";
} else {
    foreach ($files as $f) {
        $deleted = unlink($f);
        echo ($deleted ? 'DELETED' : 'FAILED ') . ': ' . basename($f) . "\n";
    }
}

echo "\n=== Compiled views ===\n";
$views = glob($base . '/storage/framework/views/*.php') ?: [];
$vcount = 0;
foreach ($views as $f) { if (unlink($f)) $vcount++; }
echo "Deleted $vcount compiled view(s)\n";

echo "\n=== Framework cache ===\n";
$deleted = 0;
$dirs = glob($base . '/storage/framework/cache/data/*') ?: [];
foreach ($dirs as $d) {
    if (!is_dir($d)) continue;
    foreach (glob($d . '/*') ?: [] as $f) {
        if (is_file($f) && unlink($f)) $deleted++;
    }
}
echo "Deleted $deleted cache file(s)\n";

echo "\n=== Sessions table ===\n";
// Load .env
$env = [];
foreach (file($base . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (str_starts_with(trim($line), '#')) continue;
    [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
    $env[trim($k)] = trim($v, '"\'');
}
try {
    $pdo = new PDO(
        'mysql:host=' . $env['DB_HOST'] . ';dbname=' . $env['DB_DATABASE'] . ';charset=utf8mb4',
        $env['DB_USERNAME'], $env['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $pdo->exec("DELETE FROM sessions");
    $pdo->exec("DELETE FROM cache");
    echo "Cleared sessions and cache tables\n";
} catch (Exception $e) {
    echo "DB error: " . $e->getMessage() . "\n";
}

echo "\n=== Error log (last 20 lines) ===\n";
// Check PHP error log
$phpLog = ini_get('error_log');
if ($phpLog && file_exists($phpLog)) {
    $lines = array_slice(file($phpLog), -20);
    echo implode('', $lines);
} else {
    echo "PHP error log: " . ($phpLog ?: 'not configured') . "\n";
}

// Also check Laravel log for NEW entries
$laravelLog = $base . '/storage/logs/laravel.log';
if (file_exists($laravelLog)) {
    $all = file($laravelLog);
    $recent = array_filter($all, fn($l) => str_contains($l, '2026-06-11'));
    echo "\nLaravel log entries from today: " . count($recent) . "\n";
    echo implode('', array_slice($recent, -10));
}

echo "\n\n=== Done — now visit the site ===\n";
echo "https://dai.ie/dai-laravel/public/\n";
echo "Then delete this file.\n";
