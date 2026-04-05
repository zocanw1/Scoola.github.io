{{-- Shared CSS Tokens — Dark/Light mode variables, base styles --}}
<style>
    /* ── SHARED TOKENS ── */
    :root {
        --sidebar-w: 220px;
        --top-h:     52px;
        --r:         10px;
    }

    /* ── DARK MODE (default) ── */
    [data-theme="dark"] {
        --navy:         #0d1117;
        --navy2:        #161b22;
        --navy3:        #1c2333;
        --navy4:        #21262d;
        --glass:        rgba(255,255,255,0.04);
        --glass-border: rgba(255,255,255,0.08);
        --glass-hover:  rgba(255,255,255,0.06);
        --accent:       #58a6ff;
        --green:        #3fb950;
        --amber:        #e3b341;
        --red:          #f85149;
        --purple:       #bc8cff;
        --text1:        #e6edf3;
        --text2:        #8b949e;
        --text3:        #484f58;
    }

    /* ── LIGHT MODE ── */
    [data-theme="light"] {
        --navy:         #f3f4f6;
        --navy2:        #ffffff;
        --navy3:        #e9ebee;
        --navy4:        #dee1e6;
        --glass:        rgba(0,0,0,0.02);
        --glass-border: rgba(0,0,0,0.08);
        --glass-hover:  rgba(0,0,0,0.04);
        --accent:       #0969da;
        --green:        #1a7f37;
        --amber:        #8a6914;
        --red:          #cf222e;
        --purple:       #7c3aed;
        --text1:        #1f2328;
        --text2:        #57606a;
        --text3:        #9198a1;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--navy);
        color: var(--text1);
        min-height: 100vh;
        font-size: 13px;
    }

    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: var(--navy); }
    ::-webkit-scrollbar-thumb { background: var(--glass-border); border-radius: 4px; }

    /* Smooth theme transition */
    html { color-scheme: dark; }
    [data-theme="light"] { color-scheme: light; }

    /* ── LIGHT MODE ADJUSTMENTS ── */
    [data-theme="light"] .sidebar { box-shadow: 2px 0 8px rgba(0,0,0,0.06); }
    [data-theme="light"] .topbar  { box-shadow: 0 1px 4px rgba(0,0,0,0.06); }

    /* ── RESPONSIVE ── */
    .hamburger-btn {
        display: none;
        width: 32px; height: 32px;
        border-radius: 7px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        place-items: center;
        font-size: 16px;
        cursor: pointer;
    }

    .sidebar-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 199;
    }

    @media (max-width: 768px) {
        :root { --sidebar-w: 0px; }

        .sidebar {
            transform: translateX(-260px);
            width: 260px;
            transition: transform .3s ease;
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
</style>
