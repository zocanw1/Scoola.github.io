{{-- Shared Topbar Styles --}}
<style>
    .main-wrapper {
        margin-left: var(--sidebar-w);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .topbar {
        height: var(--top-h);
        background: var(--navy2);
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        align-items: center;
        padding: 0 20px;
        gap: 10px;
        position: sticky; top: 0;
        z-index: 100;
    }

    .top-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 14px; font-weight: 800;
        color: var(--text1);
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
        gap: 7px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 7px;
        padding: 6px 12px;
        color: var(--text2);
        width: 200px;
    }

    .top-search input {
        background: none; border: none; outline: none;
        color: var(--text1);
        font-size: 12px;
        font-family: 'Inter', sans-serif;
        width: 100%;
    }

    .top-search input::placeholder { color: var(--text3); }

    .top-icon-btn {
        width: 32px; height: 32px;
        border-radius: 7px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        display: grid; place-items: center;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        position: relative;
        transition: all .15s;
    }

    .top-icon-btn:hover { background: var(--glass-hover); color: var(--text1); }

    .notif-dot::after {
        content: '';
        position: absolute;
        top: 5px; right: 5px;
        width: 6px; height: 6px;
        background: var(--red);
        border-radius: 50%;
        border: 1.5px solid var(--navy2);
    }

    .top-logout {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 13px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        border-radius: 7px;
        font-size: 12px; font-weight: 500;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all .15s;
    }

    .top-logout:hover { border-color: rgba(248,81,73,.5); color: var(--red); }

    .page-body {
        flex: 1;
        padding: 20px 22px;
    }

    /* ── THEME TOGGLE BUTTON ── */
    .theme-toggle {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: 7px;
        color: var(--text2);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all .2s;
        user-select: none;
        white-space: nowrap;
    }
    .theme-toggle:hover { border-color: rgba(88,166,255,.35); color: var(--text1); }

    .toggle-track {
        width: 28px; height: 16px;
        border-radius: 20px;
        background: var(--glass-border);
        position: relative;
        transition: background .3s;
        flex-shrink: 0;
    }
    [data-theme="dark"] .toggle-track  { background: rgba(88,166,255,.35); }

    .toggle-thumb {
        position: absolute;
        top: 2px; left: 2px;
        width: 12px; height: 12px;
        border-radius: 50%;
        background: var(--text2);
        transition: transform .3s cubic-bezier(.34,1.56,.64,1), background .3s;
    }
    [data-theme="dark"] .toggle-thumb  { transform: translateX(12px); background: var(--accent); }
</style>
