{{-- Shared Sidebar Styles — Premium Canvas --}}
<style>
    .sidebar {
        position: fixed;
        top: 0; left: 0;
        width: var(--sidebar-w);
        height: 100vh;
        background: var(--navy2);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border-right: 1px solid var(--glass-border);
        display: flex;
        flex-direction: column;
        z-index: 200;
        transition: box-shadow var(--transition);
    }

    .sb-brand {
        height: var(--top-h);
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 20px;
        border-bottom: 1px solid var(--glass-border);
    }

    .sb-logo {
        width: 36px; height: 36px;
        background: var(--gradient-accent);
        border-radius: 11px;
        display: grid; place-items: center;
        font-size: 16px; font-weight: 800;
        color: #fff;
        flex-shrink: 0;
        font-family: 'Outfit', sans-serif;
        box-shadow: 0 4px 16px rgba(124,58,237,0.35);
        transition: transform var(--transition-spring);
    }
    .sb-logo:hover {
        transform: scale(1.08) rotate(-3deg);
    }

    .sb-title {
        font-family: 'Outfit', sans-serif;
        font-size: 17px; font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--text1);
        background: linear-gradient(90deg, var(--text1), var(--accent-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .sb-ver {
        margin-left: auto;
        font-size: 9px;
        background: var(--accent-soft);
        border: 1px solid var(--accent-glow);
        color: var(--accent-light);
        padding: 3px 9px;
        border-radius: 6px;
        font-weight: 700;
        letter-spacing: .02em;
    }

    .sb-section {
        padding: 18px 18px 6px;
        font-size: 10px;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--text3);
        font-weight: 700;
    }

    .sb-nav {
        flex: 1;
        overflow-y: auto;
        padding: 8px 12px;
    }

    .sb-nav::-webkit-scrollbar { width: 3px; }
    .sb-nav::-webkit-scrollbar-thumb { background: var(--glass-border); border-radius: 10px; }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 10px 14px;
        border-radius: var(--r-xs);
        color: var(--text2);
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        margin-bottom: 2px;
        transition: all var(--transition);
        position: relative;
    }

    .nav-link i { font-size: 16px; width: 20px; flex-shrink: 0; text-align: center; }
    .nav-link:hover {
        background: var(--glass-hover);
        color: var(--text1);
        transform: translateX(3px);
    }

    .nav-link.active {
        background: var(--accent-soft);
        color: var(--accent-light);
        font-weight: 600;
        border: 1px solid var(--accent-glow);
    }

    .nav-link.active::before {
        content: '';
        position: absolute;
        left: 0; top: 50%;
        transform: translateY(-50%);
        width: 3px; height: 20px;
        background: var(--gradient-accent);
        border-radius: 0 4px 4px 0;
        box-shadow: 0 0 8px var(--accent-glow);
    }

    .nav-link.disabled {
        opacity: 0.30;
        pointer-events: none;
    }

    .nav-badge {
        margin-left: auto;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 9px;
        border-radius: 12px;
    }

    .nb-blue  { background: var(--accent-soft);  color: var(--accent-light); }
    .nb-red   { background: var(--red-soft);     color: var(--red); }
    .nb-green { background: var(--green-soft);   color: var(--green); }
    .nb-amber { background: var(--amber-soft);   color: var(--amber); }

    .sb-footer {
        padding: 14px 12px;
        border-top: 1px solid var(--glass-border);
    }

    .sb-user {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 11px 14px;
        background: var(--glass);
        border: 1px solid var(--glass-border);
        border-radius: var(--r-sm);
        transition: all var(--transition);
        backdrop-filter: blur(10px);
    }
    .sb-user:hover {
        border-color: var(--accent-glow);
        background: var(--accent-soft);
    }

    .sb-avatar {
        width: 34px; height: 34px;
        border-radius: 10px;
        background: var(--gradient-accent);
        display: grid; place-items: center;
        font-size: 13px; font-weight: 700;
        color: #fff; flex-shrink: 0;
        box-shadow: 0 2px 10px rgba(124,58,237,0.3);
    }

    .sb-uname {
        font-size: 12.5px; font-weight: 600;
        color: var(--text1);
        white-space: nowrap; overflow: hidden;
        text-overflow: ellipsis; max-width: 150px;
    }
    .sb-urole {
        font-size: 10px; color: var(--accent-light);
        letter-spacing: .05em; text-transform: uppercase;
        font-weight: 700;
    }

    /* ── HAMBURGER BUTTON ── */
    .hamburger-btn {
        display: none;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text1);
        width: 38px; height: 38px;
        border-radius: var(--r-xs);
        font-size: 20px;
        cursor: pointer;
        place-items: center;
        flex-shrink: 0;
        transition: all var(--transition);
        backdrop-filter: blur(10px);
    }
    .hamburger-btn:hover { background: var(--glass-hover); }

    /* ── SIDEBAR OVERLAY ── */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 199;
        opacity: 0;
        transition: opacity .3s;
    }
    .sidebar-overlay.open {
        display: block;
        opacity: 1;
    }

    /* ══ MOBILE RESPONSIVE ══ */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            width: 280px;
        }
        .sidebar.open {
            transform: translateX(0);
        }

        .hamburger-btn {
            display: grid;
        }

        .main-wrapper {
            margin-left: 0 !important;
        }
    }
</style>
