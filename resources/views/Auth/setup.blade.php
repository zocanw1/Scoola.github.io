<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoola - Project Setup</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --navy1: #0d1117;
            --navy2: #161b22;
            --navy3: #21262d;
            --accent: #58a6ff;
            --text1: #e6edf3;
            --text2: #8b949e;
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--navy1);
            color: var(--text1);
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .setup-card {
            background: var(--navy2);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--accent);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text1);
        }

        .subtitle {
            font-size: 13px;
            color: var(--text2);
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text2);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input {
            width: 100%;
            background: var(--navy3);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            padding: 12px 16px;
            color: var(--text1);
            font-size: 14px;
            box-sizing: border-box;
            transition: all 0.2s;
        }

        .input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.2);
        }

        .btn {
            width: 100%;
            background: var(--accent);
            color: var(--navy1);
            border: none;
            border-radius: 8px;
            padding: 14px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(88, 166, 255, 0.4);
        }

        .error {
            color: #f85149;
            font-size: 12px;
            margin-top: 4px;
        }

        .alert {
            background: rgba(248, 81, 73, 0.1);
            border: 1px solid rgba(248, 81, 73, 0.2);
            color: #f85149;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="setup-card">
    <div class="header">
        <div class="logo">
            <i class="bi bi-shield-lock-fill"></i> SCOOLA
        </div>
        <div class="title">Project Initializer</div>
        <div class="subtitle">Create the very first administrator account</div>
    </div>

    @if($errors->any())
        <div class="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> Mohon periksa kembali inputan Anda.
        </div>
    @endif

    <form action="{{ url('/scoola-setup') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label class="label">Full Name</label>
            <input type="text" name="name" class="input" placeholder="e.g. System Administrator" value="{{ old('name') }}" required autofocus>
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="label">Email Address</label>
            <input type="email" name="email" class="input" placeholder="admin@scoola.id" value="{{ old('email') }}" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="label">Password</label>
            <input type="password" name="password" class="input" placeholder="••••••••" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="input" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn">INITIALIZE SYSTEM</button>
    </form>
</div>

</body>
</html>
