<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    // Forward Vercel requests to Laravel's public/index.php
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<h1>Error bootstrapping application:</h1>";
    echo "<p><b>Message:</b> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><b>File:</b> " . htmlspecialchars($e->getFile()) . " on line " . $e->getLine() . "</p>";
    echo "<h2>Stack Trace:</h2>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
