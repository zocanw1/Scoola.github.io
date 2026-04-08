<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function formLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Rate limiting
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();
        $maxAttempts = config('scoola.login_max_attempts', 5);
        $decayMinutes = config('scoola.login_decay_minutes', 1);

        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.");
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            RateLimiter::hit($throttleKey, $decayMinutes * 60);
            return back()->withErrors(['email' => 'Hmm.. sepertinya email ini belum terdaftar di sistem. Coba periksa lagi!'])->withInput($request->except('password'));
        }

        if (!Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey, $decayMinutes * 60);
            return back()->withErrors(['password' => 'Oops! Kata sandi yang kamu masukkan kurang tepat. Yuk, coba lagi.'])->withInput($request->except('password'));
        }

        Auth::login($user);

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect('/admin/dashboard'),
            'guru'  => redirect('/guru/dashboard'),
            'siswa' => redirect('/siswa/dashboard'),
            default => abort(403)
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    /* =========================
       SETUP: Halaman registrasi admin tersembunyi
       Akses: /scoola-setup  (tidak ada link ke sini)
    ========================= */
    public function formSetup(Request $request)
    {
        $adminExists = User::where('role', 'admin')->exists();
        $setupSecret = config('scoola.setup_secret');

        if ($adminExists) {
            if (!$setupSecret || $request->query('secret') !== $setupSecret) {
                return redirect('/login')->with('error', 'Sistem sudah dikonfigurasi. Silakan login atau hubungi administrator.');
            }
        }

        return view('auth.setup');
    }

    public function storeSetup(Request $request)
    {
        $adminExists = User::where('role', 'admin')->exists();
        $setupSecret = config('scoola.setup_secret');

        if ($adminExists) {
            $providedSecret = $request->input('setup_secret') ?? $request->query('secret');
            if (!$setupSecret || $providedSecret !== $setupSecret) {
                return redirect('/login')->with('error', 'Akses ditolak. Setup admin hanya diperbolehkan satu kali.');
            }
        }

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        return redirect('/login')->with('success', 'Akun admin berhasil dibuat. Silakan login.');
    }
}
