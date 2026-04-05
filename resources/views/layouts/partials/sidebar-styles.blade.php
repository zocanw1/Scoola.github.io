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
    }

    .sb-brand {
        height: var(--top-h);
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 16px;
        border-bottom: 1px solid var(--glass-border);
    }

    .sb-logo {
        width: 28px; height: 28px;
        background: var(--accent);
        border-radius: 7px;
        display: grid; place-items: center;
        font-size: 14px; font-weight: 800;
        color: var(--navy);
        flex-shrink: 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .sb-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 14px; font-weight: 800;
        letter-spacing: -0.01em;
        color: var(--text1);
    }

    .sb-ver {
        margin-left: auto;
        font-size: 9px;
        background: var(--glass);
        border: 1px solid var(--glass-border);
        color: var(--text3);
        padding: 2px 6px;
        border-radius: 4px;
    }

    .sb-section {
        padding: 14px 14px 4px;
        font-size: 9px;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--text3);
        font-weight: 600;
    }

    .sb-nav {
        flex: 1;
        overflow-y: auto;
        padding: 8px 10px;
    }

    .sb-nav::-webkit-scrollbar { width: 3px; }
    .sb-nav::-webkit-scrollbar-thumb { background: var(--glass-border); }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 7px 10px;
        border-radius: 7px;
        color: var(--text2);
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 500;
        margin-bottom: 1px;
        transition: all .15s;
        position: relative;
    }

    .nav-link i { font-size: 14px; width: 16px; flex-shrink: 0; }
    .nav-link:hover { background: var(--glass-hover); color: var(--text1); }

    .nav-link.active {
        background: rgba(88,166,255,0.1);
        color: var(--accent);
    }

    .nav-link.active::before {
        content: '';
        position: absolute;
        left: 0; top: 50%;
        transform: translateY(-50%);
        width: 2px; height: 16px;
        background: var(--accent);
        border-radius: 0 2px 2px 0;
    }

    .nav-link.disabled {
        opacity: 0.4;
        pointer-events: none;
    }

    .nav-badge {
        margin-left: auto;
        font-size: 9px;
        font-weight: 600;
        padding: 1px 6px;
        border-radius: 10px;
    }

    .nb-blue  { background: rgba(88,166,255,.15);  color: var(--accent); }
    .nb-red   { background: rgba(248,81,73,.15);   color: var(--red); }
    .nb-green { background: rgba(63,185,80,.15);   color: var(--green); }
    .nb-amber { background: rgba(227,179,65,.15);  color: var(--amber); }

    .sb-footer {
        padding: 12px 10px;
        border-top: 1px solid var(--glass-border);
    }

    .sb-user {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 8px 10px;
        background: var(--glass);
        border: 1px solid var(--glass-border);
        border-radius: 8px;
    }

    .sb-avatar {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), var(--purple));
        display: grid; place-items: center;
        font-size: 11px; font-weight: 700;
        color: #fff; flex-shrink: 0;
    }

    .sb-uname { font-size: 11.5px; font-weight: 600; color: var(--text1); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px; }
    .sb-urole { font-size: 9.5px; color: var(--accent); letter-spacing: .04em; text-transform: uppercase; }
</style>
