<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
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
