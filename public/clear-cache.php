<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('route:clear');
$kernel->call('config:clear');
$kernel->call('cache:clear');
$kernel->call('view:clear');

echo 'All caches cleared. <a href="' . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/">Go to homepage</a>';
