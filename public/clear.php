<?php
$dir = dirname(__DIR__) . '/bootstrap/cache';
foreach (glob($dir . '/*') ?: [] as $f) {
    echo (unlink($f) ? 'deleted' : 'FAILED') . ': ' . basename($f) . "\n";
}
echo "\nDone — Cache CLEARED!! \n";
