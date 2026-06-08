<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scoola — Masuk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --sakura: #FF7675;
            --cyber: #00CEC9;
            --cosmo: #6C5CE7;
            --gold: #FDCB6E;
            --midnight: #1E1B29;
            --mochi: #FAF9FF;
            --white: #FFFFFF;
        }

        @keyframes pop-in {
            from { opacity: 0; transform: scale(0.95) translateY(15px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        body {
            background-color: var(--mochi);
            min-height: 100vh;
            margin: 0;
            display: flex;
            overflow-x: hidden;
            font-family: 'Nunito', 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;
            color: var(--midnight);
            background-image: radial-gradient(var(--cosmo) 1px, transparent 0);
            background-size: 24px 24px;
        }

        .editorial-split {
            display: flex;
            width: 100%;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        /* Panel Hero Kiri (Pop-Art Violet) */
        .hero-panel {
            flex: 1.2;
            min-width: 460px;
            background: var(--cosmo);
            color: var(--white);
            padding: 80px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            border-right: 6px solid var(--midnight);
            background-image: 
                linear-gradient(45deg, rgba(30, 27, 41, 0.05) 25%, transparent 25%), 
                linear-gradient(-45deg, rgba(30, 27, 41, 0.05) 25%, transparent 25%), 
                linear-gradient(45deg, transparent 75%, rgba(30, 27, 41, 0.05) 75%), 
                linear-gradient(-45deg, transparent 75%, rgba(30, 27, 41, 0.05) 75%);
            background-size: 40px 40px;
            background-position: 0 0, 0 20px, 20px -20px, -20px 0px;
        }

        /* Dekorasi Lingkaran Solid Neobrutalism */
        .hero-panel::before {
            content: '';
            position: absolute;
            top: 10%;
            right: -50px;
            width: 200px;
            height: 200px;
            background: var(--gold);
            border: 4px solid var(--midnight);
            border-radius: 50%;
            box-shadow: 6px 6px 0px 0px var(--midnight);
        }

        .hero-panel::after {
            content: '★';
            position: absolute;
            bottom: 8%;
            left: 5%;
            font-size: 80px;
            color: var(--cyber);
            text-shadow: 4px 4px 0px var(--midnight);
            -webkit-text-stroke: 3px var(--midnight);
            transform: rotate(-15deg);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .brand-text {
            display: inline-flex;
            align-items: center;
            font-family: 'Fredoka One', cursive;
            font-size: 32px;
            color: var(--gold);
            text-shadow: 3px 3px 0px var(--midnight);
            -webkit-text-stroke: 2px var(--midnight);
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 8px;
            background: var(--cyber);
            border: 3px solid var(--midnight);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--midnight);
            box-shadow: 4px 4px 0px var(--midnight);
            margin-bottom: 40px;
            transform: rotate(-2deg);
        }

        .hero-title {
            font-size: clamp(40px, 5vw, 64px);
            line-height: 1.05;
            font-weight: 800;
            margin: 20px 0;
            letter-spacing: -1px;
            font-family: 'Fredoka One', cursive;
            color: var(--white);
            text-shadow: 5px 5px 0px var(--midnight);
            -webkit-text-stroke: 2px var(--midnight);
        }

        .hero-desc {
            max-width: 480px;
            font-size: 17px;
            line-height: 1.7;
            color: var(--white);
            font-weight: 600;
            background: rgba(30, 27, 41, 0.85);
            padding: 20px;
            border-radius: 12px;
            border: 3px solid var(--midnight);
            box-shadow: 5px 5px 0px 0px var(--midnight);
            margin-top: 30px;
        }

        .hero-note {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--midnight);
            background: var(--gold);
            padding: 10px 15px;
            border: 3px solid var(--midnight);
            box-shadow: 4px 4px 0px var(--midnight);
            align-self: flex-start;
            z-index: 2;
            transform: rotate(1deg);
        }

        /* Panel Form Kanan (Neobrutalism Card Container) */
        .form-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            position: relative;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--white);
            border: 4px solid var(--midnight);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 8px 8px 0px 0px var(--midnight);
            animation: pop-in 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.15) both;
            position: relative;
        }

        /* Aksesoris Stiker Lucu di Atas Card */
        .login-card::after {
            content: '( ≧◡≦ )';
            position: absolute;
            top: -24px;
            right: 30px;
            background: var(--sakura);
            color: var(--white);
            border: 3px solid var(--midnight);
            padding: 4px 12px;
            font-family: 'Fredoka One', cursive;
            font-size: 14px;
            border-radius: 8px;
            box-shadow: 3px 3px 0px var(--midnight);
            transform: rotate(5deg);
        }

        .form-header {
            margin-bottom: 35px;
            margin-top: 20px;
            text-align: center;
        }

        .form-header span {
            display: inline-block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--cosmo);
            font-weight: 800;
            background: rgba(108, 92, 231, 0.1);
            padding: 4px 10px;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .form-header h2 {
            font-size: 38px;
            font-family: 'Fredoka One', cursive;
            color: var(--midnight);
            margin: 0;
            text-shadow: 2px 2px 0px var(--cyber);
        }

        .input-group {
            margin-bottom: 24px;
            position: relative;
        }

        .input-label {
            display: block;
            margin-bottom: 8px;
            color: var(--midnight);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 800;
        }

        .input-field {
            width: 100%;
            padding: 16px;
            border: 3px solid var(--midnight);
            border-radius: 12px;
            background: var(--mochi);
            font-size: 15px;
            font-weight: 600;
            outline: none;
            transition: all 0.2s ease;
            color: var(--midnight);
            box-shadow: 4px 4px 0px 0px var(--midnight);
        }

        .input-field::placeholder {
            color: #A1A9C0;
            font-weight: 500;
        }

        .input-field:focus {
            background: var(--white);
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px 0px var(--midnight);
            border-color: var(--cosmo);
        }

        .btn-full {
            width: 100%;
            margin-top: 24px;
            padding: 16px;
            font-family: 'Fredoka One', cursive;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 3px solid var(--midnight);
            border-radius: 14px;
            cursor: pointer;
            background: var(--sakura);
            color: var(--white);
            transition: all 0.2s ease;
            box-shadow: 5px 5px 0px var(--midnight);
            text-shadow: 2px 2px 0px var(--midnight);
        }

        .btn-full:hover {
            transform: translate(-2px, -2px);
            box-shadow: 7px 7px 0px var(--midnight);
            background: #ff5e5d;
        }

        .btn-full:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px var(--midnight);
        }

        .alert-minimal {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 13px;
            font-weight: 700;
            background: rgba(0, 206, 201, 0.1);
            border: 3px solid var(--midnight);
            box-shadow: 4px 4px 0px var(--midnight);
            color: var(--midnight);
        }

        /* Alert Khusus Error */
        .alert-error {
            background: rgba(255, 118, 117, 0.1) !important;
            border: 3px solid var(--midnight) !important;
            box-shadow: 4px 4px 0px var(--midnight) !important;
            color: var(--midnight) !important;
        }

        .error-message {
            color: var(--sakura);
            font-size: 12px;
            font-weight: 800;
            margin-top: 8px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-footer {
            margin-top: 30px;
            padding-top: 18px;
            border-top: 3px dashed var(--midnight);
            text-align: center;
        }

        .form-footer a {
            font-size: 12px;
            color: var(--cosmo);
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 800;
            transition: all 0.2s ease;
            display: inline-block;
        }

        .form-footer a:hover {
            color: var(--sakura);
            transform: scale(1.05) rotate(-1deg);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: var(--midnight);
            color: var(--white);
            text-decoration: none;
            border: 3px solid var(--midnight);
            border-radius: 10px;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
            box-shadow: 3px 3px 0px var(--midnight);
            cursor: pointer;
        }

        .btn-back:hover {
            transform: translate(-2px, -2px);
            box-shadow: 5px 5px 0px var(--midnight);
            background: var(--cosmo);
        }

        .btn-back:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px var(--midnight);
        }

        @media (max-width: 992px) {
            body { 
                overflow-y: auto; 
            }
            .editorial-split { 
                flex-direction: column; 
            }
            .hero-panel { 
                padding: 40px; 
                min-height: auto; 
                border-right: none; 
                border-bottom: 6px solid var(--midnight); 
                min-width: 100%;
            }
            .hero-title { 
                font-size: 42px; 
            }
            .hero-panel::before, .hero-panel::after { 
                display: none; 
            }
            .form-panel { 
                width: 100%; 
                padding: 40px 20px; 
            }
            .login-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<div class="editorial-split">
    <!-- Hero Panel (Sisi Kiri) -->
    <div class="hero-panel">
        <div class="hero-content">
            <div class="brand-text">Scoola</div>
            <br>
            <div class="hero-badge">✨ Professional</div>
            <div class="hero-text">
                <h1 class="hero-title">Smart Presensi Sekolah</h1>
                <p class="hero-desc">Platform kehadiran modern yang menggabungkan keseriusan profesional dan sentuhan visual yang super ceria! ⚡</p>
            </div>
        </div>
        <div class="hero-note">(✿◡‿◡) &bull; Presensi Jadi Lebih Menyenangkan!</div>
    </div>

    <!-- Form Panel (Sisi Kanan) -->
    <div class="form-panel">
        <div class="login-card">
            <a href="{{ url('/') }}" class="btn-back">
                ← Kembali ke Landing Page
            </a>

            <div class="form-header">
                <span>Selamat Datang Kembali</span>
                <h2>Login</h2>
            </div>

            <!-- Pesan Sesi Selesai / Info -->
            @if(session('info') || request()->has('expired'))
                <div class="alert-minimal">
                    ⚡ {{ session('info') ?? 'Sesi Anda telah berakhir. Silakan login kembali.' }}
                </div>
            @endif

            <!-- Pesan Error Umum -->
            @if(session('error'))
                <div class="alert-minimal alert-error">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf

                <!-- Input Email -->
                <div class="input-group">
                    <label class="input-label">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="input-field" placeholder="nama@sekolah.com">
                    @error('email')
                        <span class="error-message">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                <!-- Input Password -->
                <div class="input-group">
                    <label class="input-label">Password</label>
                    <input type="password" name="password" required class="input-field" placeholder="••••••••">
                    @error('password')
                        <span class="error-message">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn-full">
                    Masuk Sekarang ✨
                </button>

                <!-- Footer -->
                <div class="form-footer" aria-hidden="true"></div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

