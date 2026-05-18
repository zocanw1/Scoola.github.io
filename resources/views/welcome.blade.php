<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoola — Editorial Platform</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        (function() {
            const storageKey = 'scoola-theme';
            const getTheme = () => {
                try { return localStorage.getItem(storageKey) || 'light'; } 
                catch (e) { return 'light'; }
            };
            const setTheme = (theme) => {
                document.documentElement.setAttribute('data-theme', theme);
                try { localStorage.setItem(storageKey, theme); } catch (e) {}
            };
            
            setTheme(getTheme());

            window.toggleTheme = function() {
                const html = document.documentElement;
                const current = html.getAttribute('data-theme') || 'light';
                const newTheme = current === 'light' ? 'dark' : 'light';
                
                html.setAttribute('data-theme', newTheme);
                try { localStorage.setItem(storageKey, newTheme); } catch (e) {}
                console.log('Theme toggled to:', newTheme);
            };
        })();
    </script>
    
    <style>
        :root {
            --font-sans: 'Inter', system-ui, sans-serif;
        }

        body {
            background-color: #C8C8C8;
            color: var(--color-ink);
            font-family: var(--font-sans);
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }

        /* Nav Bar - Runway Style */
        .runway-nav {
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 48px;
            border-bottom: 1px solid var(--color-hairline);
            background-color: #FFFFFF;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand-logo {
            font-size: 18px;
            font-weight: 700;
            color: var(--color-ink);
            text-decoration: none;
            letter-spacing: -0.5px;
            text-transform: lowercase;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .nav-links a {
            font-size: 13px;
            font-weight: 700;
            color: var(--color-ink);
            text-decoration: none;
            transition: color 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* Hero Section */
        .hero {
            padding: 160px 48px 120px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .display-huge {
            font-size: 120px;
            font-weight: 400;
            line-height: 0.85;
            letter-spacing: -0.05em;
            color: var(--color-ink);
            margin-bottom: 40px;
            max-width: 1000px;
            text-transform: uppercase;
        }

        .hero-desc {
            font-size: 20px;
            line-height: 1.5;
            color: var(--color-ink);
            max-width: 600px;
            margin-bottom: 64px;
            font-weight: 500;
        }

        /* Features Section */
        .features {
            padding: 0 48px 120px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-md);
        }

        .feature-card {
            background: #FFFFFF;
            padding: 64px 48px;
            border: 1px solid var(--color-hairline);
            border-radius: 12px;
        }

        .feature-eyebrow {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--color-stone);
            margin-bottom: 32px;
            display: block;
        }

        .feature-title {
            font-size: 28px;
            font-weight: 500;
            letter-spacing: -0.02em;
            color: var(--color-ink);
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .feature-desc {
            font-size: 16px;
            line-height: 1.6;
            color: var(--color-graphite);
        }

        /* Footer */
        footer {
            border-top: 1px solid var(--color-hairline);
            padding: 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        @media (max-width: 900px) {
            .display-huge { font-size: 48px; letter-spacing: -1.5px; }
            .features-grid { grid-template-columns: 1fr; }
            .hero { padding: 120px 24px 80px; }
            .features { padding: 0 24px 80px; }
            .runway-nav { padding: 0 24px; }
        }
    </style>
</head>
<body>

    <nav class="runway-nav">
        <div>
            <a href="/" class="brand-logo">scoola.</a>
        </div>
        <div class="nav-links" style="position: relative; z-index: 1000;">
            <button type="button" onclick="window.toggleTheme()" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border: none; background: transparent; font-size: 20px; color: var(--color-ink); cursor: pointer; border-radius: 50%;" title="Toggle Theme">
                <i class="bi bi-moon-stars" style="pointer-events: none;"></i>
            </button>
            <a href="{{ route('login') }}" style="color: var(--color-ink); font-weight: 600;">Sign In &rarr;</a>
        </div>
    </nav>

    <section class="hero">
        <h1 class="display-huge">Academic management,<br>refined.</h1>
        <p class="hero-desc">
            Experience a new standard in educational software. Scoola delivers a high-contrast, editorial interface designed for absolute clarity and focus. No distractions, just data.
        </p>
        <a href="{{ route('login') }}" class="btn-primary" style="text-decoration: none; display: inline-flex; height: 48px; padding: 0 24px; font-size: 15px;">
            Access System
        </a>
    </section>

    <section class="features">
        <div class="features-grid">
            <div class="feature-card">
                <span class="feature-eyebrow">01 / Aesthetics</span>
                <h3 class="feature-title">Editorial Design</h3>
                <p class="feature-desc">Built on the principles of Swiss design. High-contrast typography, structural whitespace, and hairline dividers for perfect hierarchy.</p>
            </div>
            <div class="feature-card">
                <span class="feature-eyebrow">02 / Functionality</span>
                <h3 class="feature-title">Real-time Presence</h3>
                <p class="feature-desc">GPS-verified, code-based attendance tracking that works flawlessly across all devices without the need for complex hardware.</p>
            </div>
            <div class="feature-card">
                <span class="feature-eyebrow">03 / Architecture</span>
                <h3 class="feature-title">Absolute Control</h3>
                <p class="feature-desc">Comprehensive administrative tools for managing schedules, teachers, and student data with surgical precision.</p>
            </div>
        </div>
    </section>

    <footer>
        <span class="text-micro-caps" style="color: var(--color-stone)">© 2026 Scoola. All rights reserved.</span>
        <div style="display: flex; gap: 24px;">
            <a href="#" class="text-micro-caps" style="color: var(--color-stone); text-decoration: none;">System Status</a>
            <a href="#" class="text-micro-caps" style="color: var(--color-stone); text-decoration: none;">Documentation</a>
        </div>
    </footer>

</body>
</html>
