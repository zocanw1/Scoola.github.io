{{-- Shared CSS Tokens — Dark/Light mode variables, base styles --}}
<style>
    /* ── SHARED TOKENS ── */
    :root {
        --sidebar-w: 240px;
        --top-h:     58px;
        --r:         14px;
        --r-sm:      10px;
        --r-xs:      8px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.08);
        --shadow-md: 0 4px 14px rgba(0,0,0,0.15), 0 2px 6px rgba(0,0,0,0.1);
        --shadow-lg: 0 10px 40px rgba(0,0,0,0.2), 0 4px 12px rgba(0,0,0,0.1);
        --transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-spring: 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* ── DARK MODE (default) ── */
    [data-theme="dark"] {
        --navy:         #0a0e14;
        --navy2:        #12171f;
        --navy3:        #1a2030;
        --navy4:        #222a3a;
        --glass:        var(--glass-border);
        --glass-border: rgba(255,255,255,0.07);
        --glass-hover:  rgba(255,255,255,0.06);
        --accent:       #60a5fa;
        --accent-soft:  rgba(96,165,250,0.12);
        --accent-glow:  rgba(96,165,250,0.25);
        --green:        #34d399;
        --green-soft:   rgba(52,211,153,0.12);
        --amber:        #fbbf24;
        --amber-soft:   rgba(251,191,36,0.12);
        --red:          #f87171;
        --red-soft:     rgba(248,113,113,0.12);
        --purple:       #a78bfa;
        --purple-soft:  rgba(167,139,250,0.12);
        --cyan:         #22d3ee;
        --text1:        #f1f5f9;
        --text2:        #94a3b8;
        --text3:        #475569;
        --gb:           rgba(255,255,255,0.06);
        --gh:           var(--glass-border);
        --gradient-card: linear-gradient(135deg, var(--glass-border) 0%, rgba(255,255,255,0.01) 100%);
        --gradient-accent: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%);
        --gradient-success: linear-gradient(135deg, #34d399 0%, #22d3ee 100%);
        --input-bg:     var(--glass-border);
    }

    /* ── LIGHT MODE ── */
    [data-theme="light"] {
        --navy:         #f1f5f9;
        --navy2:        #ffffff;
        --navy3:        #f1f5f9;
        --navy4:        #e2e8f0;
        --glass:        rgba(0,0,0,0.02);
        --glass-border: rgba(0,0,0,0.08);
        --glass-hover:  rgba(0,0,0,0.04);
        --accent:       #3b82f6;
        --accent-soft:  rgba(59,130,246,0.1);
        --accent-glow:  rgba(59,130,246,0.2);
        --green:        #10b981;
        --green-soft:   rgba(16,185,129,0.1);
        --amber:        #f59e0b;
        --amber-soft:   rgba(245,158,11,0.1);
        --red:          #ef4444;
        --red-soft:     rgba(239,68,68,0.1);
        --purple:       #8b5cf6;
        --purple-soft:  rgba(139,92,246,0.1);
        --cyan:         #06b6d4;
        --text1:        #0f172a;
        --text2:        #475569;
        --text3:        #94a3b8;
        --gb:           rgba(0,0,0,0.06);
        --gh:           rgba(0,0,0,0.03);
        --gradient-card: linear-gradient(135deg, rgba(255,255,255,1) 0%, rgba(241,245,249,1) 100%);
        --gradient-accent: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        --gradient-success: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
        --input-bg:     rgba(0,0,0,0.03);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--navy);
        color: var(--text1);
        min-height: 100vh;
        font-size: 13.5px;
        line-height: 1.5;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--glass-border); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--text3); }

    /* Smooth theme transition */
    html { color-scheme: dark; }
    [data-theme="light"] { color-scheme: light; }

    /* ── LIGHT MODE ADJUSTMENTS ── */
    [data-theme="light"] .sidebar { box-shadow: 2px 0 12px rgba(0,0,0,0.06); }
    [data-theme="light"] .topbar  { box-shadow: 0 1px 6px rgba(0,0,0,0.05); }

    /* ── RESPONSIVE ── */
    .hamburger-btn {
        display: none;
        width: 36px; height: 36px;
        border-radius: var(--r-xs);
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        place-items: center;
        font-size: 18px;
        cursor: pointer;
        transition: all var(--transition);
    }
    .hamburger-btn:hover { background: var(--glass-hover); color: var(--text1); }

    .sidebar-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
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
        from { opacity: 0; transform: translateY(16px); }
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
    .fi { animation: fadeInUp .5s cubic-bezier(.4,0,.2,1) both; }
    .d1{animation-delay:.04s} .d2{animation-delay:.08s} .d3{animation-delay:.12s}
    .d4{animation-delay:.16s} .d5{animation-delay:.20s} .d6{animation-delay:.24s}
    .d7{animation-delay:.28s} .d8{animation-delay:.32s}
</style>
