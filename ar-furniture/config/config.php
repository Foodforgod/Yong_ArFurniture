<?php
// Simple .env parser for native PHP without external packages
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        return;
    }
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2) + [null, null];
        if ($name && $value) {
            $name = trim($name);
            $value = trim($value, "\"'");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Load .env from root directory
loadEnv(__DIR__ . '/../.env');

return [
    'app' => [
        'name' => $_ENV['APP_NAME'] ?? 'AR Furniture Catalog',
        'url' => $_ENV['APP_URL'] ?? ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'))
    ],
    'db' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'name' => $_ENV['DB_NAME'] ?? 'ar_furniture',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'pass' => $_ENV['DB_PASS'] ?? ''
    ]
];