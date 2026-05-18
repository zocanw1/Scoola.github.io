{{-- Shared Topbar Styles — Premium Canvas --}}
<style>
    .main-wrapper {
        margin-left: var(--sidebar-w);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        transition: margin-left var(--transition);
    }

    .topbar {
        height: var(--top-h);
        background: var(--navy2);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        align-items: center;
        padding: 0 28px;
        gap: 14px;
        position: sticky; top: 0;
        z-index: 100;
    }

    .top-title {
        font-family: 'Outfit', sans-serif;
        font-size: 16px; font-weight: 700;
        color: var(--text1);
        letter-spacing: -0.01em;
    }

    .top-breadcrumb {
        font-size: 11px;
        color: var(--text3);
    }

    .top-breadcrumb span { color: var(--text2); }

    .top-spacer { flex: 1; }

    .top-search {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--input-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--r-sm);
        padding: 8px 16px;
        color: var(--text2);
        width: 240px;
        transition: all var(--transition);
        backdrop-filter: blur(10px);
    }
    .top-search:focus-within {
        border-color: var(--accent-glow);
        box-shadow: 0 0 0 4px rgba(124,58,237,0.08);
        width: 280px;
    }

    .top-search input {
        background: none; border: none; outline: none;
        color: var(--text1);
        font-size: 13px;
        font-family: 'Outfit', sans-serif;
        width: 100%;
    }

    .top-search input::placeholder { color: var(--text3); }

    .top-icon-btn {
        width: 36px; height: 36px;
        border-radius: var(--r-xs);
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        display: grid; place-items: center;
        font-size: 15px;
        cursor: pointer;
        text-decoration: none;
        position: relative;
        transition: all var(--transition);
    }

    .top-icon-btn:hover { background: var(--glass-hover); color: var(--text1); }

    .notif-dot::after {
        content: '';
        position: absolute;
        top: 5px; right: 5px;
        width: 7px; height: 7px;
        background: var(--red);
        border-radius: 50%;
        border: 1.5px solid var(--navy2-solid);
    }

    .top-logout {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        border-radius: var(--r-xs);
        font-size: 12.5px; font-weight: 500;
        cursor: pointer;
        font-family: 'Outfit', sans-serif;
        transition: all var(--transition);
        white-space: nowrap;
    }

    .top-logout:hover {
        border-color: rgba(248,113,113,.3);
        color: var(--red);
        background: var(--red-soft);
    }

    .page-body {
        flex: 1;
        padding: 28px 32px;
    }

    /* ── THEME TOGGLE BUTTON ── */
    .theme-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: var(--r-xs);
        color: var(--text2);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        font-family: 'Outfit', sans-serif;
        transition: all var(--transition);
        user-select: none;
        white-space: nowrap;
    }
    .theme-toggle:hover { border-color: var(--accent-glow); color: var(--text1); }

    .toggle-track {
        width: 32px; height: 18px;
        border-radius: 20px;
        background: var(--glass-border);
        position: relative;
        transition: background .3s;
        flex-shrink: 0;
    }
    [data-theme="dark"] .toggle-track  { background: var(--accent-glow); }

    .toggle-thumb {
        position: absolute;
        top: 3px; left: 3px;
        width: 12px; height: 12px;
        border-radius: 50%;
        background: var(--text2);
        transition: transform .3s cubic-bezier(.34,1.56,.64,1), background .3s;
    }
    [data-theme="dark"] .toggle-thumb  { transform: translateX(14px); background: var(--accent-light); }

    /* ══ MOBILE TOPBAR ══ */
    @media (max-width: 768px) {
        .topbar {
            padding: 0 14px;
            gap: 8px;
            height: 54px;
        }

        .top-search {
            display: none;
        }

        .top-title {
            font-size: 14px;
        }

        .top-breadcrumb {
            display: none;
        }

        .top-logout span,
        .top-logout .d-none-mobile {
            display: none;
        }

        .theme-toggle span {
            display: none !important;
        }

        .theme-toggle {
            padding: 6px 8px;
        }

        .top-logout {
            padding: 7px 10px;
            font-size: 14px;
        }

        .page-body {
            padding: 18px 16px;
        }
    }

    @media (max-width: 480px) {
        .topbar {
            padding: 0 10px;
            gap: 6px;
        }

        .page-body {
            padding: 14px 12px;
        }
    }
</style>
