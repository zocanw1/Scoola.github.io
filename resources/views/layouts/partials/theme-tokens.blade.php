{{-- Shared CSS Tokens — Premium Canvas Design System --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    /* ── SHARED TOKENS ── */
    :root {
        --sidebar-w: 260px;
        --top-h:     62px;
        --r:         16px;
        --r-sm:      12px;
        --r-xs:      8px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.08);
        --shadow-md: 0 4px 20px rgba(0,0,0,0.15), 0 2px 8px rgba(0,0,0,0.1);
        --shadow-lg: 0 12px 48px rgba(0,0,0,0.25), 0 4px 16px rgba(0,0,0,0.12);
        --transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-spring: 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* ── DARK MODE (default) ── */
    [data-theme="dark"] {
        --navy:         #030014;
        --navy2:        rgba(255, 255, 255, 0.03);
        --navy2-solid:  #0a0a1a;
        --navy3:        rgba(255, 255, 255, 0.05);
        --navy4:        rgba(255, 255, 255, 0.08);
        --glass:        rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.08);
        --glass-hover:  rgba(255, 255, 255, 0.06);
        --accent:       #7C3AED;
        --accent-light: #A78BFA;
        --accent-soft:  rgba(124,58,237,0.12);
        --accent-glow:  rgba(124,58,237,0.30);
        --green:        #34d399;
        --green-soft:   rgba(52,211,153,0.12);
        --amber:        #fbbf24;
        --amber-soft:   rgba(251,191,36,0.12);
        --red:          #f87171;
        --red-soft:     rgba(248,113,113,0.12);
        --purple:       #a78bfa;
        --purple-soft:  rgba(167,139,250,0.12);
        --cyan:         #22d3ee;
        --cyan-soft:    rgba(34,211,238,0.12);
        --text1:        #f1f5f9;
        --text2:        #94a3b8;
        --text3:        #475569;
        --gb:           rgba(255,255,255,0.06);
        --gh:           rgba(255,255,255,0.08);
        --gradient-card: linear-gradient(135deg, rgba(255,255,255,0.04) 0%, rgba(255,255,255,0.01) 100%);
        --gradient-accent: linear-gradient(135deg, #7C3AED 0%, #00D1FF 100%);
        --gradient-success: linear-gradient(135deg, #34d399 0%, #22d3ee 100%);
        --input-bg:     rgba(255,255,255,0.04);
        --blob1:        rgba(112, 0, 255, 0.12);
        --blob2:        rgba(0, 209, 255, 0.10);
    }

    /* ── LIGHT MODE ── */
    [data-theme="light"] {
        --navy:         #f0f2f5;
        --navy2:        rgba(255,255,255,0.7);
        --navy2-solid:  #ffffff;
        --navy3:        #ffffff;
        --navy4:        rgba(0,0,0,0.06);
        --glass:        rgba(255,255,255,0.5);
        --glass-border: rgba(0,0,0,0.08);
        --glass-hover:  rgba(0,0,0,0.04);
        --accent:       #7C3AED;
        --accent-light: #8B5CF6;
        --accent-soft:  rgba(124,58,237,0.08);
        --accent-glow:  rgba(124,58,237,0.20);
        --green:        #10b981;
        --green-soft:   rgba(16,185,129,0.1);
        --amber:        #f59e0b;
        --amber-soft:   rgba(245,158,11,0.1);
        --red:          #ef4444;
        --red-soft:     rgba(239,68,68,0.1);
        --purple:       #8b5cf6;
        --purple-soft:  rgba(139,92,246,0.1);
        --cyan:         #06b6d4;
        --cyan-soft:    rgba(6,182,212,0.1);
        --text1:        #0f172a;
        --text2:        #475569;
        --text3:        #94a3b8;
        --gb:           rgba(0,0,0,0.04);
        --gh:           rgba(0,0,0,0.03);
        --gradient-card: linear-gradient(135deg, rgba(255,255,255,0.8) 0%, rgba(241,245,249,0.6) 100%);
        --gradient-accent: linear-gradient(135deg, #7C3AED 0%, #00A3CC 100%);
        --gradient-success: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
        --input-bg:     #ffffff;
        --blob1:        rgba(124, 58, 237, 0.06);
        --blob2:        rgba(0, 209, 255, 0.05);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--navy);
        color: var(--text1);
        min-height: 100vh;
        font-size: 13.5px;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        position: relative;
    }

    /* ── ANIMATED BACKGROUND BLOBS ── */
    .bg-canvas {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: -1;
        overflow: hidden;
        pointer-events: none;
    }

    .bg-canvas .blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(120px);
        animation: blobFloat 25s infinite alternate ease-in-out;
    }

    .bg-canvas .blob-1 {
        width: 600px; height: 600px;
        background: var(--blob1);
        top: -150px; left: -100px;
    }

    .bg-canvas .blob-2 {
        width: 500px; height: 500px;
        background: var(--blob2);
        bottom: -100px; right: -80px;
        animation-delay: -12s;
    }

    .bg-canvas .blob-3 {
        width: 350px; height: 350px;
        background: var(--blob1);
        top: 40%; right: 20%;
        animation-delay: -8s;
        opacity: 0.5;
    }

    @keyframes blobFloat {
        0%   { transform: translate(0, 0) scale(1); }
        33%  { transform: translate(30px, -50px) scale(1.05); }
        66%  { transform: translate(-20px, 30px) scale(0.95); }
        100% { transform: translate(50px, 20px) scale(1.02); }
    }

    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--glass-border); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--text3); }

    /* Smooth theme transition */
    html { color-scheme: dark; }
    [data-theme="light"] { color-scheme: light; }

    /* ── LIGHT MODE ADJUSTMENTS ── */
    [data-theme="light"] .sidebar { box-shadow: 2px 0 20px rgba(0,0,0,0.06); }
    [data-theme="light"] .topbar  { box-shadow: 0 2px 12px rgba(0,0,0,0.04); }

    /* ── RESPONSIVE ── */
    .hamburger-btn {
        display: none;
        width: 38px; height: 38px;
        border-radius: var(--r-xs);
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        place-items: center;
        font-size: 18px;
        cursor: pointer;
        transition: all var(--transition);
        backdrop-filter: blur(10px);
    }
    .hamburger-btn:hover { background: var(--glass-hover); color: var(--text1); }

    .sidebar-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 199;
    }

    @media (max-width: 768px) {
        :root { --sidebar-w: 0px; }

        .sidebar {
            transform: translateX(-280px);
            width: 280px;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .sidebar-overlay.open {
            display: block;
        }

        .hamburger-btn {
            display: grid;
        }

        .main-wrapper {
            margin-left: 0 !important;
        }

        .topbar {
            padding: 0 14px;
        }

        .top-search { display: none !important; }
    }

    /* ── UTILITY ANIMATIONS ── */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-12px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .6; }
    }
    @keyframes shimmer {
        0%   { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    .fi { animation: fadeInUp .6s cubic-bezier(.4,0,.2,1) both; }
    .d1{animation-delay:.05s} .d2{animation-delay:.10s} .d3{animation-delay:.15s}
    .d4{animation-delay:.20s} .d5{animation-delay:.25s} .d6{animation-delay:.30s}
    .d7{animation-delay:.35s} .d8{animation-delay:.40s}
</style>
