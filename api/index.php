<?php

use Illuminate\Http\Request;

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

try {
    // 4. Bootstrapping Aplikasi Laravel
    $app = require __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath('/tmp/storage');

    // 5. Eksekusi Request
    if (method_exists($app, 'handleRequest')) {
        $app->handleRequest(Request::capture());
    } else {
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        $response = $kernel->handle(
            $request = Request::capture()
        );
        $response->send();
        $kernel->terminate($request, $response);
    }
} catch (\Throwable $e) {
    // Tampilkan detail error asli langsung ke layar jika terjadi crash
    http_response_code(500);
    header('Content-Type: text/html');
    
    echo '<div style="font-family: sans-serif; padding: 20px; background: #fff0f0; color: #900; border: 1px solid #f00; rounded: 8px;">';
    echo '<h2>Laravel Crash Exception</h2>';
    echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ' (line ' . $e->getLine() . ')</p>';
    echo '<h3>Stack Trace:</h3>';
    echo '<pre style="background: #222; color: #0f0; padding: 15px; overflow-x: auto; border-radius: 5px;">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '</div>';
    exit;
}