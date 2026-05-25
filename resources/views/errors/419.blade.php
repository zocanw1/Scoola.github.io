<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Berakhir — Scoola</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite('resources/css/app.css')
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

        .error-container {
            text-align: center;
            padding: 48px;
        }

        .error-code {
            font-size: 120px;
            font-weight: 400;
            line-height: 1;
            letter-spacing: -4px;
            margin-bottom: 24px;
            color: var(--color-ink);
        }

        .error-title {
            font-size: 24px;
            font-weight: 400;
            letter-spacing: -0.5px;
            margin-bottom: 16px;
            text-transform: uppercase;
        }

        .error-desc {
            font-size: 15px;
            color: var(--color-stone);
            max-width: 320px;
            margin: 0 auto 48px;
            line-height: 1.6;
        }

        .redirect-status {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--color-ink);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .dot-pulse {
            width: 4px;
            height: 4px;
            background: var(--color-ink);
            border-radius: 50%;
            animation: pulse 1s infinite alternate;
        }

        @keyframes pulse {
            from { opacity: 1; }
            to { opacity: 0.2; }
        }
    </style>
</head>
<body>

<div class="error-container">
    <div class="error-code">419</div>
    <h1 class="error-title">Sesi Berakhir</h1>
    <p class="error-desc">Keamanan sesi Anda telah kedaluwarsa. Kami akan mengarahkan Anda kembali ke halaman masuk.</p>
    
    <div class="redirect-status">
        <div class="dot-pulse"></div>
        Mengarahkan otomatis
    </div>
</div>

<script>
    setTimeout(function() {
        window.location.href = "{{ route('login') }}?expired=1";
    }, 2000);
</script>

</body>
</html>
