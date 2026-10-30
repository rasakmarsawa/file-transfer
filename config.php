<?php

// Load environment variables from .env file
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv($key . '=' . $value);
            }
        }
    }
}

define('ADMIN_PASSWORD', $_ENV['ADMIN_PASSWORD'] ?? '');
define('ADMIN_COOKIE_NAME', $_ENV['ADMIN_COOKIE_NAME'] ?? 'user');
define('ADMIN_COOKIE_VALUE', $_ENV['ADMIN_COOKIE_VALUE'] ?? 'admin');
define('ADMIN_COOKIE_LIFETIME', (int)($_ENV['ADMIN_COOKIE_LIFETIME'] ?? 300));