<?php
// DELETE AFTER USE — shows raw Apache/PHP server vars
header('Content-Type: text/plain; charset=utf-8');
$keys = ['REQUEST_URI','SCRIPT_NAME','SCRIPT_FILENAME','PHP_SELF',
         'DOCUMENT_ROOT','HTTP_HOST','HTTPS','SERVER_PORT',
         'PATH_INFO','PATH_TRANSLATED','REDIRECT_URL','REDIRECT_STATUS'];
foreach ($keys as $k) {
    echo str_pad($k, 25) . ': ' . ($_SERVER[$k] ?? '(not set)') . "\n";
}
echo "\n--- All REDIRECT_* vars ---\n";
foreach ($_SERVER as $k => $v) {
    if (str_starts_with($k, 'REDIRECT_')) echo "$k: $v\n";
}
echo "\n--- .htaccess files found on disk ---\n";
$paths = [
    '/home/sites/27a/b/b8cdb6fa26/public_html/.htaccess',
    '/home/sites/27a/b/b8cdb6fa26/public_html/dai-laravel/.htaccess',
    '/home/sites/27a/b/b8cdb6fa26/public_html/dai-laravel/public/.htaccess',
];
foreach ($paths as $p) {
    echo $p . ': ' . (file_exists($p) ? 'EXISTS' : 'NOT FOUND') . "\n";
}
