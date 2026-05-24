<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Force stateless runtime defaults on Vercel before config is loaded.
$isVercelRuntime = getenv('VERCEL')
    || getenv('NOW_REGION')
    || getenv('VERCEL_ENV')
    || getenv('VERCEL_URL')
    || isset($_ENV['VERCEL'])
    || isset($_ENV['NOW_REGION'])
    || isset($_ENV['VERCEL_ENV'])
    || isset($_ENV['VERCEL_URL']);
$forceStateless = filter_var(
    $_ENV['VERCEL_FORCE_STATELESS'] ?? getenv('VERCEL_FORCE_STATELESS') ?? 'true',
    FILTER_VALIDATE_BOOL
);
if ($isVercelRuntime && $forceStateless) {
    putenv('SESSION_DRIVER=cookie');
    putenv('CACHE_STORE=file');
    $_ENV['SESSION_DRIVER'] = 'cookie';
    $_ENV['CACHE_STORE'] = 'file';
    $_SERVER['SESSION_DRIVER'] = 'cookie';
    $_SERVER['CACHE_STORE'] = 'file';
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
