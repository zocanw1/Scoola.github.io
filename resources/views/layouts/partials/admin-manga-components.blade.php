<style>
    .mp-page {
        display: flex;
        flex-direction: column;
        gap: 32px;
        padding-bottom: 48px;
        color: var(--midnight);
        font-family: 'Nunito', 'Inter', system-ui, sans-serif;
    }

    .mp-hero,
    .mp-card,
    .mp-form-card,
    .mp-table-card,
    .mp-stat-card,
    .mp-empty-state,
    .mp-alert {
        position: relative;
        background: var(--white);
        color: var(--midnight);
        border: 4px solid var(--midnight);
        border-radius: 16px;
        box-shadow: 8px 8px 0 var(--midnight);
    }

    .mp-hero {
        overflow: hidden;
        padding: 40px;
        background: var(--cosmo);
        color: var(--white);
    }

    .mp-hero-wrap {
        position: relative;
        margin-top: 34px;
    }

    .mp-hero::after {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        right: -42px;
        bottom: -42px;
        border: 4px solid var(--midnight);
        border-radius: 999px;
        background: var(--gold);
        box-shadow: 6px 6px 0 var(--midnight);
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
        text-transform: uppercase;
        letter-spacing: 0.12em;
    }

    .mp-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        margin-bottom: 18px;
        border: 3px solid var(--midnight);
        border-radius: 10px;
        background: var(--cyber);
        color: var(--midnight);
        box-shadow: 4px 4px 0 var(--midnight);
        font-size: 12px;
        transform: rotate(-1deg);
    }

    .mp-title {
        margin: 0 0 18px;
        color: var(--white);
        font-family: 'Fredoka One', cursive;
        font-size: clamp(34px, 5vw, 54px);
        line-height: 1.08;
        letter-spacing: 0;
        text-shadow: 4px 4px 0 var(--midnight);
        -webkit-text-stroke: 1.5px var(--midnight);
    }

    .mp-description {
        max-width: 680px;
        margin: 0;
        padding: 16px 18px;
        color: var(--midnight);
        background: var(--white);
        border: 3px solid var(--midnight);
        border-radius: 12px;
        box-shadow: 4px 4px 0 var(--midnight);
        font-size: 15px;
        font-weight: 800;
        line-height: 1.6;
    }

    .mp-sticker {
        position: absolute;
        z-index: 2;
        top: -20px;
        right: 30px;
        padding: 6px 14px;
        border: 3px solid var(--midnight);
        border-radius: 10px;
        background: var(--gold);
        color: var(--midnight);
        box-shadow: 3px 3px 0 var(--midnight);
        font-size: 12px;
        font-weight: 900;
        transform: rotate(4deg);
        white-space: nowrap;
    }

    .mp-card,
    .mp-form-card,
    .mp-table-card,
    .mp-stat-card,
    .mp-alert {
        padding: 30px;
    }

    .mp-form-card {
        max-width: 980px;
    }

    .mp-card-cyber { background: var(--cyber); }
    .mp-card-gold { background: var(--gold); }
    .mp-card-sakura { background: var(--sakura); color: var(--white); }

    .mp-hover {
        transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
    }

    .mp-hover:hover {
        transform: translate(-2px, -2px);
        box-shadow: 10px 10px 0 var(--midnight);
    }

    .mp-hover:active {
        transform: translate(2px, 2px);
        box-shadow: 3px 3px 0 var(--midnight);
    }

    .mp-stats-grid,
    .mp-selection-grid,
    .mp-form-grid {
        display: grid;
        gap: 28px;
    }

    .mp-stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }

    .mp-selection-grid {
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    }

    .mp-form-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .mp-stat-card {
        display: flex;
        align-items: center;
        gap: 22px;
        min-height: 148px;
        overflow: hidden;
    }

    .mp-stat-icon {
        width: 66px;
        height: 66px;
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 4px solid var(--midnight);
        border-radius: 18px;
        background: var(--white);
        box-shadow: 4px 4px 0 var(--midnight);
        font-size: 28px;
        color: var(--midnight);
    }

    .mp-stat-label,
    .mp-label {
        color: var(--midnight);
        font-size: 12px;
    }

    .mp-stat-value {
        color: var(--midnight);
        font-family: 'Fredoka One', cursive;
        font-size: 52px;
        line-height: 1;
        text-shadow: 2px 2px 0 var(--white);
    }

    .mp-field {
        margin-bottom: 28px;
    }

    .mp-label {
        display: block;
        margin-bottom: 10px;
    }

    .mp-page input.mp-input,
    .mp-page select.mp-input,
    .mp-page textarea.mp-input {
        width: 100% !important;
        min-height: 52px;
        padding: 14px 16px !important;
        background: var(--mochi) !important;
        color: var(--midnight) !important;
        border: 3px solid var(--midnight) !important;
        border-radius: 12px !important;
        box-shadow: 4px 4px 0 var(--midnight) !important;
        font-family: 'Nunito', sans-serif !important;
        font-size: 15px !important;
        font-weight: 800 !important;
        outline: none !important;
        transition: all 0.16s ease !important;
    }

    .mp-page input.mp-input:focus,
    .mp-page select.mp-input:focus,
    .mp-page textarea.mp-input:focus {
        background: var(--white) !important;
        border-color: var(--cosmo) !important;
        transform: translate(-2px, -2px) !important;
        box-shadow: 6px 6px 0 var(--midnight) !important;
    }

    .mp-page input.mp-input[readonly],
    .mp-page select.mp-input[disabled] {
        background: #ece9f8 !important;
        cursor: not-allowed;
    }

    .mp-hint {
        display: block;
        margin-top: 10px;
        color: var(--midnight);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        opacity: 0.75;
    }

    .mp-checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 14px;
        padding: 20px;
        background: var(--mochi);
        border: 3px solid var(--midnight);
        border-radius: 14px;
        box-shadow: 4px 4px 0 var(--midnight);
    }

    .mp-check {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--midnight);
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
    }

    .mp-check input {
        width: 18px;
        height: 18px;
        accent-color: var(--sakura);
        flex: 0 0 auto;
    }

    .mp-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        align-items: center;
        padding-top: 26px;
        margin-top: 8px;
        border-top: 3px dashed var(--midnight);
    }

    .mp-btn,
    .mp-btn-secondary {
        min-height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 0 24px;
        border: 3px solid var(--midnight);
        border-radius: 12px;
        box-shadow: 4px 4px 0 var(--midnight);
        color: var(--midnight) !important;
        font-family: 'Fredoka One', cursive;
        font-size: 13px;
        letter-spacing: 0.03em;
        text-decoration: none !important;
        cursor: pointer;
        transition: all 0.16s ease;
    }

    .mp-btn {
        background: var(--sakura);
    }

    .mp-btn-secondary {
        background: var(--white);
    }

    .mp-btn-green {
        background: #107c41;
        color: var(--white) !important;
    }

    .mp-btn:hover,
    .mp-btn-secondary:hover {
        transform: translate(-2px, -2px);
        box-shadow: 7px 7px 0 var(--midnight);
    }

    .mp-btn:active,
    .mp-btn-secondary:active {
        transform: translate(2px, 2px);
        box-shadow: 2px 2px 0 var(--midnight);
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

    .mp-table-wrap::-webkit-scrollbar {
        height: 10px;
    }

    .mp-table-wrap::-webkit-scrollbar-track {
        background: rgba(30, 27, 41, 0.08);
        border-radius: 999px;
    }

    .mp-table-wrap::-webkit-scrollbar-thumb {
        background: var(--cosmo);
        border: 2px solid rgba(250, 249, 255, 0.95);
        border-radius: 999px;
    }

    .mp-table-wrap::-webkit-scrollbar-thumb:hover {
        background: var(--midnight);
    }

    .mp-table {
        width: 100%;
        min-width: 760px;
        border-collapse: collapse;
        color: var(--midnight);
        font-family: 'Nunito', sans-serif;
    }

    .mp-table th {
        padding: 18px 20px;
        background: var(--gold);
        color: var(--midnight);
        border-right: 3px solid var(--midnight);
        border-bottom: 4px solid var(--midnight);
        font-family: 'Fredoka One', cursive;
        font-size: 14px;
        text-align: left;
    }

    .mp-table th:last-child,
    .mp-table td:last-child {
        border-right: 0;
    }

    .mp-table td {
        padding: 18px 20px;
        background: var(--white);
        color: var(--midnight);
        border-right: 3px solid var(--midnight);
        border-bottom: 3px solid var(--midnight);
        font-weight: 800;
        vertical-align: middle;
    }

    .mp-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .mp-table tbody tr:hover td {
        background: var(--mochi);
    }

    .mp-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border: 2px solid var(--midnight);
        border-radius: 999px;
        background: var(--cyber);
        color: var(--midnight);
        box-shadow: 2px 2px 0 var(--midnight);
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .mp-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .mp-tab {
        display: inline-flex;
        align-items: center;
        min-height: 40px;
        padding: 0 16px;
        border: 3px solid var(--midnight);
        border-radius: 999px;
        background: var(--white);
        color: var(--midnight) !important;
        box-shadow: 3px 3px 0 var(--midnight);
        font-weight: 900;
        text-decoration: none !important;
        text-transform: uppercase;
        font-size: 12px;
    }

    .mp-tab.active {
        background: var(--cyber);
    }

    .mp-select-card {
        display: block;
        padding: 30px;
        background: var(--white);
        color: var(--midnight) !important;
        border: 4px solid var(--midnight);
        border-radius: 16px;
        box-shadow: 7px 7px 0 var(--midnight);
        text-decoration: none !important;
    }

    .mp-select-title {
        margin: 10px 0;
        color: var(--midnight);
        font-family: 'Fredoka One', cursive;
        font-size: 30px;
        line-height: 1.1;
    }

    .mp-alert {
        background: var(--gold);
    }

    .mp-alert.danger {
        background: #ffe7e7;
    }

    .mp-empty-state {
        padding: 54px 28px;
        text-align: center;
    }

    .mp-fab {
        width: 62px !important;
        height: 62px !important;
        position: fixed;
        right: 38px;
        bottom: 38px;
        z-index: 9999;
        border: 4px solid var(--midnight);
        border-radius: 50%;
        background: var(--gold);
        color: var(--midnight) !important;
        box-shadow: 6px 6px 0 var(--midnight);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        font-size: 28px;
        transition: all 0.18s ease;
    }

    .mp-fab:hover {
        transform: translate(-2px, -2px);
        box-shadow: 9px 9px 0 var(--midnight);
        background: var(--sakura);
    }

    .mp-mono {
        font-family: ui-monospace, SFMono-Regular, Consolas, monospace;
    }

    .mp-right { text-align: right; }
    .mp-center { text-align: center; }

    @media (max-width: 768px) {
        .mp-hero,
        .mp-card,
        .mp-form-card,
        .mp-stat-card,
        .mp-alert {
            padding: 24px;
            box-shadow: 6px 6px 0 var(--midnight);
        }

        .mp-hero-wrap {
            margin-top: 28px;
        }

        .mp-title {
            font-size: 32px;
        }

        .mp-hero::after {
            width: 124px;
            height: 124px;
            right: -34px;
            bottom: -34px;
        }

        .mp-form-grid,
        .mp-selection-grid,
        .mp-stats-grid {
            grid-template-columns: 1fr;
        }

        .mp-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .mp-btn,
        .mp-btn-secondary {
            width: 100%;
        }

        .mp-fab {
            right: 20px;
            bottom: 20px;
        }

        .mp-sticker {
            top: -16px;
            right: 18px;
        }
    }
</style>
