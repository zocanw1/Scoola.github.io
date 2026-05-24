<?php

try {
    // Forward Vercel requests to Laravel's public/index.php
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    error_log((string) $e);

    $appDebug = $_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? getenv('APP_DEBUG') ?? false;

    if (filter_var($appDebug, FILTER_VALIDATE_BOOL)) {
        echo "<h1>Error bootstrapping application:</h1>";
        echo "<p><b>Message:</b> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><b>File:</b> " . htmlspecialchars($e->getFile()) . " on line " . $e->getLine() . "</p>";
        echo "<h2>Stack Trace:</h2>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        return;
    }

    echo 'Application error.';
}
