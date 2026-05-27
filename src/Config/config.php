<?php

declare(strict_types=1);

use App\Core\Env;

return [
    'app' => [
        'env' => Env::get('APP_ENV', 'local'),
        'url' => Env::get('APP_URL', 'http://localhost:8080'),
    ],
    'db' => [
        'host' => Env::get('DB_HOST', '127.0.0.1'),
        'port' => (int) Env::get('DB_PORT', '3306'),
        'name' => Env::get('DB_NAME', 'blog'),
        'user' => Env::get('DB_USER', 'root'),
        'password' => Env::get('DB_PASSWORD', ''),
    ],
    'paths' => [
        'templates' => dirname(__DIR__, 2) . '/templates',
        'compile' => dirname(__DIR__, 2) . '/storage/compile',
        'cache' => dirname(__DIR__, 2) . '/storage/cache',
    ],
];
