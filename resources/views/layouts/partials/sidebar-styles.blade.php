{{-- Shared Sidebar Styles --}}
<style>
    .sidebar {
        position: fixed;
        top: 0; left: 0;
        width: var(--sidebar-w);
        height: 100vh;
        background: var(--navy2);
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
        gap: 11px;
        padding: 0 18px;
        border-bottom: 1px solid var(--glass-border);
    }

    .sb-logo {
        width: 32px; height: 32px;
        background: var(--gradient-accent);
        border-radius: 9px;
        display: grid; place-items: center;
        font-size: 15px; font-weight: 800;
        color: #fff;
        flex-shrink: 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
        box-shadow: 0 2px 8px rgba(96,165,250,0.3);
    }

    .sb-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 16px; font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--text1);
    }

    .sb-ver {
        margin-left: auto;
        font-size: 9px;
        background: var(--accent-soft);
        border: 1px solid rgba(96,165,250,0.15);
        color: var(--accent);
        padding: 2px 7px;
        border-radius: 5px;
        font-weight: 600;
    }

    .sb-section {
        padding: 16px 16px 6px;
        font-size: 10px;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--text3);
        font-weight: 700;
    }

    .sb-nav {
        flex: 1;
        overflow-y: auto;
        padding: 6px 10px;
    }

    .sb-nav::-webkit-scrollbar { width: 3px; }
    .sb-nav::-webkit-scrollbar-thumb { background: var(--glass-border); border-radius: 10px; }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        border-radius: var(--r-xs);
        color: var(--text2);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 2px;
        transition: all var(--transition);
        position: relative;
    }

    .nav-link i { font-size: 15px; width: 18px; flex-shrink: 0; text-align: center; }
    .nav-link:hover { background: var(--glass-hover); color: var(--text1); }

    .nav-link.active {
        background: var(--accent-soft);
        color: var(--accent);
        font-weight: 600;
    }

    .nav-link.active::before {
        content: '';
        position: absolute;
        left: 0; top: 50%;
        transform: translateY(-50%);
        width: 3px; height: 18px;
        background: var(--accent);
        border-radius: 0 3px 3px 0;
    }

    .nav-link.disabled {
        opacity: 0.35;
        pointer-events: none;
    }

    .nav-badge {
        margin-left: auto;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 12px;
    }

    .nb-blue  { background: var(--accent-soft);  color: var(--accent); }
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
        gap: 10px;
        padding: 10px 12px;
        background: var(--glass);
        border: 1px solid var(--glass-border);
        border-radius: var(--r-sm);
        transition: border-color var(--transition);
    }
    .sb-user:hover { border-color: var(--accent-glow); }

    .sb-avatar {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: var(--gradient-accent);
        display: grid; place-items: center;
        font-size: 12px; font-weight: 700;
        color: #fff; flex-shrink: 0;
    }

    .sb-uname {
        font-size: 12px; font-weight: 600;
        color: var(--text1);
        white-space: nowrap; overflow: hidden;
        text-overflow: ellipsis; max-width: 140px;
    }
    .sb-urole {
        font-size: 10px; color: var(--accent);
        letter-spacing: .04em; text-transform: uppercase;
        font-weight: 600;
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
    }
    .hamburger-btn:hover { background: var(--glass-hover); }

    /* ── SIDEBAR OVERLAY ── */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
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
