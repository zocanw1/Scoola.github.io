<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoola — Project Setup</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --font-sans: 'Inter', system-ui, sans-serif;
        }

        body {
            background-color: var(--color-canvas);
            color: var(--color-ink);
            font-family: var(--font-sans);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }

        .setup-container {
            width: 100%;
            max-width: 440px;
            padding: 48px;
        }

        .brand {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
            text-transform: lowercase;
            margin-bottom: 64px;
            display: block;
            text-decoration: none;
            color: var(--color-ink);
        }

        .setup-header {
            margin-bottom: 48px;
        }

        .setup-title {
            font-size: 32px;
            font-weight: 400;
            line-height: 1.1;
            letter-spacing: -0.8px;
            margin-bottom: 12px;
        }

        .setup-subtitle {
            font-size: 15px;
            color: var(--color-stone);
            line-height: 1.5;
        }

        .input-group {
            margin-bottom: 40px;
        }

        .input-label {
            display: block;
            margin-bottom: 8px;
            color: var(--color-stone);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .input-field {
            width: 100%;
            padding: 12px 0;
            border: none;
            border-bottom: 1px solid var(--color-hairline-soft);
            font-size: 16px;
            background: transparent;
            outline: none;
            transition: border-color 0.3s;
            color: var(--color-ink);
            font-family: var(--font-sans);
        }

        .input-field:focus {
            border-bottom-color: var(--color-ink);
        }

        .btn-setup {
            width: 100%;
            margin-top: 24px;
            background: var(--color-primary);
            color: var(--color-on-primary);
            border: none;
            padding: 16px;
            font-weight: 600;
            font-size: 15px;
            border-radius: 99px;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-setup:hover {
            opacity: 0.9;
        }

        .alert-error {
            padding: 16px;
            border: 1px solid var(--color-ink);
            color: var(--color-ink);
            font-size: 13px;
            margin-bottom: 40px;
            background: var(--color-canvas-warm);
        }
    </style>
</head>
<body>

<div class="setup-container">
    <a href="/" class="brand">scoola.</a>
    
    <div class="setup-header">
        <h1 class="setup-title">System Initializer</h1>
        <p class="setup-subtitle">Create the primary administrator account to begin project management.</p>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/scoola-setup') }}" method="POST">
        @csrf
        
        <div class="input-group">
            <label class="input-label">Full Name</label>
            <input type="text" name="name" class="input-field" placeholder="System Administrator" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="input-group">
            <label class="input-label">Email Address</label>
            <input type="email" name="email" class="input-field" placeholder="admin@scoola.id" value="{{ old('email') }}" required>
        </div>

        <div class="input-group">
            <label class="input-label">Password</label>
            <input type="password" name="password" class="input-field" placeholder="••••••••" required>
        </div>

        <div class="input-group">
            <label class="input-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="input-field" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-setup">Initialize System</button>
    </form>
</div>

</body>
</html>
