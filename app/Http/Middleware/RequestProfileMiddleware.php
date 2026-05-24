<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class RequestProfileMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $isVercel = (bool) (
            getenv('VERCEL')
            || getenv('NOW_REGION')
            || getenv('VERCEL_ENV')
            || isset($_ENV['VERCEL'])
            || isset($_ENV['NOW_REGION'])
            || isset($_ENV['VERCEL_ENV'])
        );
        $forceStateless = filter_var(
            $_ENV['VERCEL_FORCE_STATELESS'] ?? getenv('VERCEL_FORCE_STATELESS') ?? 'true',
            FILTER_VALIDATE_BOOL
        );
        if ($isVercel && $forceStateless) {
            Config::set('session.driver', 'cookie');
            Config::set('cache.default', 'file');
        }

        $enabled = (bool) env('PERF_PROFILE', false) || $request->query('__profile') === '1';
        $requestStart = microtime(true);
        $queryCount = 0;
        $queryTotalMs = 0.0;

        if ($enabled) {
            DB::listen(function ($query) use (&$queryCount, &$queryTotalMs): void {
                $queryCount++;
                $queryTotalMs += (float) $query->time;
            });
        }

        $response = $next($request);

        $totalMs = (microtime(true) - $requestStart) * 1000;

        if ($enabled) {
            $bootstrapMs = (microtime(true) - LARAVEL_START) * 1000 - $totalMs;
            $bootstrapMs = max(0, $bootstrapMs);
            $path = '/' . ltrim($request->path(), '/');

            $response->headers->set('X-Perf-Total-Ms', number_format($totalMs, 2, '.', ''));
            $response->headers->set('X-Perf-Bootstrap-Ms', number_format($bootstrapMs, 2, '.', ''));
            $response->headers->set('X-Perf-Db-Count', (string) $queryCount);
            $response->headers->set('X-Perf-Db-Ms', number_format($queryTotalMs, 2, '.', ''));
            $response->headers->set('X-Perf-Db-Connection', (string) DB::getDefaultConnection());
            $response->headers->set('X-Perf-Session-Driver', (string) Config::get('session.driver'));
            $response->headers->set('X-Perf-Cache-Store', (string) Config::get('cache.default'));

            $thresholdMs = (float) env('PERF_SLOW_REQUEST_MS', 1200);
            if ($totalMs >= $thresholdMs) {
                Log::warning('slow_request', [
                    'path' => $path,
                    'method' => $request->method(),
                    'status' => $response->getStatusCode(),
                    'total_ms' => round($totalMs, 2),
                    'bootstrap_ms' => round($bootstrapMs, 2),
                    'db_count' => $queryCount,
                    'db_ms' => round($queryTotalMs, 2),
                ]);
            }
        }

        return $response;
    }
}
