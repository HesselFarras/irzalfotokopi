<?php

// 1. Load Composer Autoload
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Siapkan folder temporary di /tmp untuk Vercel
$folders = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($folders as $folder) {
    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }
}

// 3. Set environment variable storage ke /tmp
putenv('APP_STORAGE_PATH=/tmp/storage');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');

// 4. Gunakan 'require' (BUKAN require_once) agar instance $app selalu fresh di tiap request
$app = require __DIR__ . '/../bootstrap/app.php';

// Override storage path
$app->useStoragePath('/tmp/storage');

// 5. Eksekusi Request (Support Laravel 10 & Laravel 11)
if (method_exists($app, 'handleRequest')) {
    $app->handleRequest(Illuminate\Http\Request::capture());
} else {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    $response->send();
    $kernel->terminate($request, $response);
}