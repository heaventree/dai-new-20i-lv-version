<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

echo "DB_HOST: " . $_ENV['DB_HOST'] . "\n";
echo "DB_DATABASE: " . $_ENV['DB_DATABASE'] . "\n";
echo "DB_USERNAME: " . $_ENV['DB_USERNAME'] . "\n";
echo "DB_PASSWORD: [" . $_ENV['DB_PASSWORD'] . "]\n";

try {
    $pdo = new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    echo "\nDB CONNECTED OK";
} catch (\Exception $e) {
    echo "\nDB ERROR: " . $e->getMessage();
}