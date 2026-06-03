<style>
    .mp-page {
        display: flex;
        flex-direction: column;
        gap: 28px;
        padding-bottom: max(48px, env(safe-area-inset-bottom, 0px));
        color: var(--admin-dark, #101828);
        font-family: var(--font-sans, Arial, Helvetica, sans-serif);
    }

    .mp-mobile-only { display: none; }
    .mp-desktop-only { display: block; }

    .mp-hero,
    .mp-card,
    .mp-form-card,
    .mp-table-card,
    .mp-stat-card,
    .mp-empty-state,
    .mp-alert,
    .mp-select-card {
        position: relative;
        background: var(--admin-white, #ffffff);
        color: var(--admin-dark, #101828);
        border: 1px solid var(--admin-line, #eaecf0);
        border-radius: var(--admin-radius, 22px);
        box-shadow: var(--admin-shadow-sm, 0 10px 28px rgba(16, 24, 40, 0.06));
    }

    .mp-hero-wrap {
        position: relative;
        margin-top: 6px;
    }

    .mp-hero {
        overflow: hidden;
        padding: 38px;
        background:
            radial-gradient(circle at 12% 8%, rgba(37, 99, 235, 0.08), transparent 30%),
            linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    }

    .mp-hero::after {
        content: "";
        position: absolute;
        inset: auto 28px 28px auto;
        width: 132px;
        height: 132px;
        border-radius: 28px;
        background: var(--admin-soft-blue, #eff6ff);
        border: 1px solid var(--admin-blue-line, #dbeafe);
        transform: rotate(7deg);
        opacity: 0.85;
    }

    .mp-hero-content {
        position: relative;
        z-index: 1;
        max-width: 760px;
    }

    .mp-kicker,
    .mp-label,
    .mp-small {
        font-weight: 900;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .mp-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        margin-bottom: 18px;
        border: 1px solid var(--admin-blue-line, #dbeafe);
        border-radius: 999px;
        background: var(--admin-soft-blue, #eff6ff);
        color: var(--admin-blue, #2563eb);
        font-size: 12px;
    }

    .mp-title {
        margin: 0 0 14px;
        color: var(--admin-dark, #101828);
        font-family: var(--font-sans, Arial, Helvetica, sans-serif);
        font-size: clamp(34px, 5vw, 52px);
        font-weight: 900;
        line-height: 1.05;
        letter-spacing: 0;
        text-shadow: none;
        -webkit-text-stroke: 0;
    }

    .mp-description {
        max-width: 700px;
        margin: 0;
        color: var(--admin-muted, #667085);
        font-size: 16px;
        font-weight: 700;
        line-height: 1.7;
    }

    .mp-sticker {
        position: absolute;
        z-index: 2;
        top: -13px;
        right: 28px;
        padding: 7px 13px;
        border: 1px solid var(--admin-blue-line, #dbeafe);
        border-radius: 999px;
        background: var(--admin-white, #ffffff);
        color: var(--admin-blue, #2563eb);
        box-shadow: var(--admin-shadow-sm, 0 10px 28px rgba(16, 24, 40, 0.06));
        font-size: 11px;
        font-weight: 900;
        white-space: nowrap;
    }

    .mp-card,
    .mp-form-card,
    .mp-table-card,
    .mp-stat-card,
    .mp-alert {
        padding: 28px;
    }

    .mp-form-card { max-width: 980px; }
    .mp-card-cyber,
    .mp-card-gold,
    .mp-card-sakura {
        background: var(--admin-white, #ffffff);
        color: var(--admin-dark, #101828);
    }

    .mp-hover {
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    }

    .mp-hover:hover {
        transform: translateY(-4px);
        border-color: var(--admin-blue-line, #dbeafe);
        box-shadow: var(--admin-shadow-md, 0 24px 70px rgba(16, 24, 40, 0.09));
    }

    .mp-hover:active {
        transform: translateY(-1px);
        box-shadow: var(--admin-shadow-sm, 0 10px 28px rgba(16, 24, 40, 0.06));
    }

    .mp-stats-grid,
    .mp-selection-grid,
    .mp-form-grid {
        display: grid;
        gap: 22px;
    }

    .mp-stats-grid { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
    .mp-selection-grid { grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }
    .mp-form-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }

    .mp-stat-card {
        display: flex;
        align-items: center;
        gap: 18px;
        min-height: 130px;
    }

    .mp-stat-icon {
        width: 58px;
        height: 58px;
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--admin-blue-line, #dbeafe);
        border-radius: 18px;
        background: var(--admin-soft-blue, #eff6ff);
        color: var(--admin-blue, #2563eb);
        font-size: 24px;
    }

    .mp-stat-label,
    .mp-label {
        color: var(--admin-muted, #667085);
        font-size: 12px;
    }

    .mp-stat-value {
        color: var(--admin-dark, #101828);
        font-family: var(--font-sans, Arial, Helvetica, sans-serif);
        font-size: 44px;
        font-weight: 900;
        line-height: 1;
        letter-spacing: 0;
        text-shadow: none;
    }

    .mp-field { margin-bottom: 24px; }
    .mp-label { display: block; margin-bottom: 10px; }

    .mp-page input.mp-input,
    .mp-page select.mp-input,
    .mp-page textarea.mp-input {
        width: 100% !important;
        min-height: 50px;
        padding: 13px 15px !important;
        background: var(--admin-white, #ffffff) !important;
        color: var(--admin-dark, #101828) !important;
        border: 1px solid #d0d5dd !important;
        border-radius: 14px !important;
        box-shadow: none !important;
        font-family: var(--font-sans, Arial, Helvetica, sans-serif) !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        outline: none !important;
        transition: border-color 0.18s ease, box-shadow 0.18s ease !important;
    }

    .mp-page input.mp-input:focus,
    .mp-page select.mp-input:focus,
    .mp-page textarea.mp-input:focus {
        border-color: #93c5fd !important;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10) !important;
        transform: none !important;
    }

    .mp-page input.mp-input[readonly],
    .mp-page select.mp-input[disabled] {
        background: #f9fafb !important;
        color: var(--admin-muted, #667085) !important;
        cursor: not-allowed;
    }

    .mp-hint {
        display: block;
        margin-top: 9px;
        color: var(--admin-muted, #667085);
        font-size: 12px;
        font-weight: 800;
    }

    .mp-checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 12px;
        padding: 18px;
        background: #f9fafb;
        border: 1px solid var(--admin-line, #eaecf0);
        border-radius: 18px;
    }

    .mp-check {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--admin-dark, #101828);
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
    }

    .mp-check input {
        width: 18px;
        height: 18px;
        accent-color: var(--admin-blue, #2563eb);
        flex: 0 0 auto;
    }

    .mp-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        align-items: center;
        padding-top: 24px;
        margin-top: 8px;
        border-top: 1px solid var(--admin-line, #eaecf0);
    }

    .mp-btn,
    .mp-btn-secondary {
        min-height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0 21px;
        border: 1px solid transparent;
        border-radius: 12px;
        box-shadow: none;
        font-family: var(--font-sans, Arial, Helvetica, sans-serif);
        font-size: 14px;
        font-weight: 900;
        letter-spacing: 0;
        text-decoration: none !important;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }

    .mp-btn,
    .mp-btn-green {
        background: var(--admin-blue, #2563eb);
        color: #ffffff !important;
        border-color: var(--admin-blue, #2563eb);
    }

    .mp-btn-secondary {
        background: var(--admin-white, #ffffff);
        color: var(--admin-text, #344054) !important;
        border-color: #d0d5dd;
    }

    .mp-btn:hover,
    .mp-btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(16, 24, 40, 0.10);
    }

    .mp-btn:active,
    .mp-btn-secondary:active {
        transform: translateY(0);
        box-shadow: none;
    }

    .mp-touch-grid,
    .mp-stack-list {
        display: grid;
        gap: 14px;
    }

    .mp-action-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .mp-sticky-action {
        position: sticky;
        bottom: max(12px, env(safe-area-inset-bottom, 0px));
        z-index: 8;
        padding: 14px;
        border: 1px solid var(--admin-line, #eaecf0);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: var(--admin-shadow-md, 0 24px 70px rgba(16, 24, 40, 0.09));
        backdrop-filter: blur(10px);
    }

    .mp-table-card {
        padding: 0;
        overflow: hidden;
    }

    .mp-table-wrap {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        padding-bottom: 8px;
    }

    .mp-table-wrap::-webkit-scrollbar { height: 10px; }
    .mp-table-wrap::-webkit-scrollbar-track {
        background: #f2f4f7;
        border-radius: 999px;
    }
    .mp-table-wrap::-webkit-scrollbar-thumb {
        background: #c7d2fe;
        border-radius: 999px;
    }
    .mp-table-wrap::-webkit-scrollbar-thumb:hover { background: var(--admin-blue, #2563eb); }

    .mp-table {
        width: 100%;
        min-width: 760px;
        border-collapse: collapse;
        color: var(--admin-dark, #101828);
        font-family: var(--font-sans, Arial, Helvetica, sans-serif);
    }

    .mp-table th {
        padding: 17px 20px;
        background: #f8fbff;
        color: var(--admin-muted, #667085);
        border-bottom: 1px solid var(--admin-line, #eaecf0);
        font-family: var(--font-sans, Arial, Helvetica, sans-serif);
        font-size: 12px;
        font-weight: 900;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .mp-table td {
        padding: 18px 20px;
        background: var(--admin-white, #ffffff);
        color: var(--admin-text, #344054);
        border-bottom: 1px solid var(--admin-line, #eaecf0);
        font-weight: 800;
        vertical-align: middle;
    }

    .mp-table tbody tr:last-child td { border-bottom: 0; }
    .mp-table tbody tr:hover td { background: #fbfcfe; }

    .mp-badge,
    .mp-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .mp-badge {
        padding: 6px 12px;
        border: 1px solid var(--admin-blue-line, #dbeafe);
        background: var(--admin-soft-blue, #eff6ff);
        color: var(--admin-blue, #2563eb);
        box-shadow: none;
    }

    .mp-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 11px;
    }

    .mp-tab {
        min-height: 40px;
        padding: 0 16px;
        border: 1px solid var(--admin-line, #eaecf0);
        background: var(--admin-white, #ffffff);
        color: var(--admin-text, #344054) !important;
        text-decoration: none !important;
    }

    .mp-tab.active {
        background: var(--admin-soft-blue, #eff6ff);
        border-color: var(--admin-blue-line, #dbeafe);
        color: var(--admin-blue, #2563eb) !important;
    }

    .mp-select-card {
        display: block;
        padding: 28px;
        color: var(--admin-dark, #101828) !important;
        text-decoration: none !important;
    }

    .mp-select-title {
        margin: 10px 0;
        color: var(--admin-dark, #101828);
        font-family: var(--font-sans, Arial, Helvetica, sans-serif);
        font-size: 28px;
        font-weight: 900;
        line-height: 1.1;
    }

    .mp-alert {
        background: #fff7ed;
        border-color: #fed7aa;
    }

    .mp-alert.danger {
        background: #fef2f2;
        border-color: #fecaca;
    }

    .mp-empty-state {
        padding: 52px 28px;
        text-align: center;
    }

    .mp-fab {
        width: 58px !important;
        height: 58px !important;
        position: fixed;
        right: 38px;
        bottom: 38px;
        z-index: 9999;
        border: 1px solid var(--admin-blue, #2563eb);
        border-radius: 16px;
        background: var(--admin-blue, #2563eb);
        color: #ffffff !important;
        box-shadow: 0 18px 38px rgba(37, 99, 235, 0.22);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        font-size: 26px;
        transition: all 0.2s ease;
    }

    .mp-fab:hover {
        transform: translateY(-3px);
        box-shadow: 0 24px 52px rgba(37, 99, 235, 0.28);
    }

    .mp-mono { font-family: ui-monospace, SFMono-Regular, Consolas, monospace; }
    .mp-right { text-align: right; }
    .mp-center { text-align: center; }

    @media (hover: none) and (pointer: coarse) {
        .mp-hover:hover,
        .mp-btn:hover,
        .mp-btn-secondary:hover,
        .mp-table tbody tr:hover td {
            transform: none;
            box-shadow: inherit;
            background: inherit;
        }

        .mp-btn:active,
        .mp-btn-secondary:active {
            transform: scale(0.98);
        }
    }

    @media (max-width: 768px) {
        .mp-hero,
        .mp-card,
        .mp-form-card,
        .mp-stat-card,
        .mp-alert {
            padding: 24px;
        }

        .mp-mobile-only { display: block; }
        .mp-desktop-only { display: none !important; }
        .mp-hero-wrap { margin-top: 4px; }
        .mp-title { font-size: 32px; }
        .mp-description { max-width: 100%; font-size: 14px; line-height: 1.6; }
        .mp-hero::after { width: 96px; height: 96px; right: -18px; bottom: -18px; }
        .mp-form-grid,
        .mp-selection-grid,
        .mp-stats-grid { grid-template-columns: 1fr; }
        .mp-action-grid-2,
        .mp-touch-grid { grid-template-columns: 1fr; }
        .mp-actions { flex-direction: column; align-items: stretch; }
        .mp-btn,
        .mp-btn-secondary { width: 100%; min-height: 52px; }
        .mp-badge { white-space: normal; justify-content: center; text-align: center; }
        .mp-sticky-action { left: 0; right: 0; margin-top: 20px; }
        .mp-stack-list { gap: 12px; }
        .mp-fab { right: 20px; bottom: 20px; border-radius: 16px; }
        .mp-sticker { top: -14px; right: 18px; }
    }
</style>
