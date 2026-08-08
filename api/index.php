<?php

// 1. Require Composer Autoloader terlebih dahulu (WAJIB)
require __DIR__ . '/../vendor/autoload.php';

// 2. Siapkan folder temporary di /tmp Vercel (karena serverless bersifat Read-Only)
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

// 3. Bootstrapping Aplikasi Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Override Storage Path ke /tmp
$app->useStoragePath('/tmp/storage');

// 5. Jalankan Kernel HTTP
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);