<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->prepend(\App\Http\Middleware\RequestProfileMiddleware::class);
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        // Paksa redirect ke login dengan parameter expired jika tidak terautentikasi
        $middleware->redirectGuestsTo(fn() => route('login', ['expired' => 1]));
    })
    ->withExceptions(function (Illuminate\Foundation\Configuration\Exceptions $exceptions) {
        // Jika sesi habis saat kirim Form (POST)
        $exceptions->renderable(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            return redirect()->route('login')->with('info', 'Sesi Anda telah berakhir karena tidak ada aktivitas. Silakan login kembali.');
        });

        // Jika sesi habis saat klik Menu (GET)
        $exceptions->respond(function ($response) {
            if ($response->getStatusCode() === 401) {
                return redirect()->route('login')->with('info', 'Sesi Anda telah berakhir. Silakan login kembali untuk melanjutkan.');
            }
            return $response;
        });
    })
    ->create();

if (getenv('VERCEL') || getenv('NOW_REGION') || isset($_ENV['VERCEL']) || isset($_ENV['NOW_REGION'])) {
    $storagePath = '/tmp/storage';
    $bootstrapPath = '/tmp/bootstrap';
    $folders = [
        $storagePath,
        $storagePath . '/framework',
        $storagePath . '/framework/views',
        $storagePath . '/framework/cache',
        $storagePath . '/framework/sessions',
        $storagePath . '/logs',
        $bootstrapPath,
        $bootstrapPath . '/cache',
    ];
    foreach ($folders as $folder) {
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
    }
    $app->useStoragePath($storagePath);
    $app->useBootstrapPath($bootstrapPath);

    // Keep request path stateless on Vercel unless explicitly opted back into DB state.
    $allowDatabaseState = filter_var(
        $_ENV['VERCEL_ALLOW_DATABASE_STATE'] ?? getenv('VERCEL_ALLOW_DATABASE_STATE') ?? 'false',
        FILTER_VALIDATE_BOOL
    );
    if (! $allowDatabaseState) {
        putenv('VERCEL_FORCE_STATELESS=true');
        putenv('SESSION_DRIVER=cookie');
        putenv('CACHE_STORE=file');
        $_ENV['VERCEL_FORCE_STATELESS'] = 'true';
        $_ENV['SESSION_DRIVER'] = 'cookie';
        $_ENV['CACHE_STORE'] = 'file';
        $_SERVER['VERCEL_FORCE_STATELESS'] = 'true';
        $_SERVER['SESSION_DRIVER'] = 'cookie';
        $_SERVER['CACHE_STORE'] = 'file';
    }
}

return $app;
