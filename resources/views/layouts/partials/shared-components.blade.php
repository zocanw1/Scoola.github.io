{{-- Shared Component Styles — Used by all layouts --}}
<style>
    /* ══════════════════════════════════════════════
       PAGE HEADER
       ══════════════════════════════════════════════ */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: var(--spacing-xxl);
        gap: var(--spacing-md);
        flex-wrap: wrap;
    }

    .page-header .page-title {
        font-family: var(--font-family-base);
        font-size: 32px;
        font-weight: 400;
        color: var(--color-ink);
        line-height: 1;
        letter-spacing: var(--tracking-tight);
        text-transform: uppercase;
    }

    .page-header .page-subtitle {
        font-size: 14px;
        color: var(--color-slate);
        margin-top: var(--spacing-xs);
        max-width: 600px;
    }

    /* ══════════════════════════════════════════════
       CARDS
       ══════════════════════════════════════════════ */
    .card,
    .form-card,
    .schedule-container,
    .list-card {
        background: var(--color-surface); /* Pure White #FFFFFF */
        border: 1px solid var(--color-hairline);
        border-radius: var(--rounded-none); /* Editorial sharp corners or minimal sm */
        transition: border-color 0.2s;
    }

    .card-inner { padding: 32px; }

    .card-title {
        font-family: var(--font-family-base);
        font-size: 14px;
        font-weight: 600;
        color: var(--color-ink);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-title-action {
        margin-left: auto;
        font-size: 11px;
        color: var(--color-stone);
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* ══════════════════════════════════════════════
       BUTTONS
       ══════════════════════════════════════════════ */
    /* Buttons already defined in runway.css but kept here for consistency if needed locally */
    .btn-submit {
        background: var(--color-primary);
        color: var(--color-on-primary);
        padding: 0 32px;
        height: 48px;
        border-radius: var(--rounded-full);
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        cursor: pointer;
        transition: filter 0.2s;
    }
    .btn-submit:hover { filter: invert(1); }

    .btn-cancel {
        background: transparent;
        color: var(--color-stone);
        padding: 0 32px;
        height: 48px;
        border-radius: var(--rounded-full);
        border: 1px solid var(--color-hairline);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-edit, .btn-danger {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 8px 16px;
        border-radius: var(--rounded-xs);
        text-decoration: none;
        border: 1px solid var(--color-hairline);
    }

    .btn-edit { color: var(--color-ink); }
    .btn-danger { color: var(--color-slate); }

    /* ══════════════════════════════════════════════
       FORMS
       ══════════════════════════════════════════════ */
    .form-card {
        padding: 28px;
        max-width: 620px;
    }

    .form-group { margin-bottom: 22px; }

    .form-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: var(--color-stone);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .form-control, .form-select {
        width: 100%;
        background-color: var(--color-surface);
        border: 1px solid var(--color-hairline);
        border-radius: var(--rounded-xs);
        padding: 12px 16px;
        color: var(--color-ink);
        font-size: 14px;
        font-family: var(--font-family-base);
        outline: none;
        transition: border-color 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--color-ink);
    }

    /* Fix Autofill in Dark Mode */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus {
        -webkit-text-fill-color: var(--text1) !important;
        -webkit-box-shadow: 0 0 0px 1000px var(--navy3) inset !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    ::selection {
        background: var(--accent);
        color: #fff;
    }

    select.form-control,
    .form-select {
        appearance: none;
        background-repeat: no-repeat !important;
        background-position: right 14px center !important;
        background-size: 12px 12px !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
        padding-right: 42px !important;
        cursor: pointer;
    }

    select.form-control option,
    .form-select option {
        background: var(--navy2);
        color: var(--text1);
        padding: 10px 14px;
        font-size: 13px;
    }

    select.form-control option:checked,
    .form-select option:checked {
        background: var(--accent) !important;
        color: #fff !important;
    }

    select.form-control option:disabled,
    .form-select option:disabled {
        color: #64748b;
    }

    [data-theme="light"] select.form-control,
    [data-theme="light"] .form-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 14px center !important;
        background-size: 12px 12px !important;
    }

    [data-theme="light"] select.form-control option,
    [data-theme="light"] .form-select option {
        background: #ffffff;
        color: #1e293b;
    }

    [data-theme="light"] select.form-control option:checked,
    [data-theme="light"] .form-select option:checked {
        background: #eff6ff;
        color: #2563eb;
    }

    [data-theme="light"] select.form-control option:disabled,
    [data-theme="light"] .form-select option:disabled {
        color: #94a3b8;
    }

    .form-hint {
        font-size: 11.5px; color: var(--text3); margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid var(--glass-border);
    }

    .grid-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0 22px;
    }

    .validation-error { color: var(--red); font-size: 11.5px; margin-top: 5px; font-weight: 500; }

    /* ══════════════════════════════════════════════
       ALERTS
       ══════════════════════════════════════════════ */
    .alert-danger {
        background: var(--red-soft);
        border: 1px solid rgba(248,113,113,0.2);
        border-radius: var(--r-sm);
        padding: 16px 20px;
        margin-bottom: 24px;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 20px;
        color: var(--red);
        font-size: 13px;
    }

    .alert-success {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 20px;
        background: var(--green-soft);
        border: 1px solid rgba(52,211,153,0.2);
        border-radius: var(--r-sm);
        color: var(--green);
        font-size: 13.5px;
        margin-bottom: 24px;
        animation: fadeInUp .3s ease;
    }
    .alert-success i { font-size: 18px; }

    .alert-error {
        background: var(--red-soft);
        border: 1px solid rgba(248,113,113,0.2);
        color: var(--red);
        padding: 12px 18px;
        border-radius: var(--r-sm);
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 18px;
        display: flex; align-items: center; gap: 8px;
    }

    /* ══════════════════════════════════════════════
       TABLES
       ══════════════════════════════════════════════ */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 16px 24px;
        background: var(--color-surface);
        font-size: 11px;
        font-weight: 700;
        color: var(--color-stone);
        text-transform: uppercase;
        letter-spacing: 0.15em;
        text-align: left;
        border-bottom: 2px solid var(--color-ink);
    }

    .data-table td {
        padding: 16px 24px;
        border-bottom: 1px solid var(--color-hairline);
        color: var(--color-ink);
        font-size: 14px;
    }

    .data-table tr:hover td {
        background: var(--color-canvas-warm);
    }

    /* ══════════════════════════════════════════════
       BADGES
       ══════════════════════════════════════════════ */
    .badge-status {
        font-size: 10px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: var(--rounded-full);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid var(--color-hairline);
        color: var(--color-ink);
    }

    .bs-h { background: #000; color: #fff; border-color: #000; } /* Hadir - Black */
    .bs-a { background: transparent; color: var(--color-stone); } /* Alpha - Muted */

    .hari-badge {
        background: var(--accent-soft);
        color: var(--accent);
        padding: 4px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 12px;
        border: 1px solid rgba(124,58,237,0.12);
    }

    .jam-badge {
        background: var(--green-soft);
        color: var(--green);
        padding: 4px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 12px;
        border: 1px solid rgba(52,211,153,0.12);
    }

    .nis-badge {
        display: inline-flex; align-items: center; gap: 7px;
        background: var(--accent-soft);
        border: 1px solid rgba(124,58,237,0.12);
        color: var(--accent);
        padding: 9px 16px;
        border-radius: var(--r-xs);
        font-family: 'Outfit', sans-serif;
        font-size: 14px; font-weight: 700;
        letter-spacing: .03em;
    }

    /* ══════════════════════════════════════════════
       CLASS SELECTOR (JADWAL)
       ══════════════════════════════════════════════ */
    .class-selector {
        display: flex;
        gap: 10px;
        margin-bottom: 26px;
        flex-wrap: wrap;
    }

    .class-btn {
        padding: 8px 20px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: var(--r-sm);
        color: var(--text2);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all var(--transition);
    }
    .class-btn:hover {
        border-color: var(--accent-glow);
        color: var(--text1);
        background: var(--accent-soft);
    }
    .class-btn.active {
        background: var(--gradient-accent);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 3px 12px rgba(124,58,237,0.3);
    }

    /* ══════════════════════════════════════════════
       MOBILE RESPONSIVE — CONTENT
       ══════════════════════════════════════════════ */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 14px;
        }

        .page-header .page-title {
            font-size: 19px;
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .form-card {
            padding: 20px 18px;
            max-width: 100%;
        }

        .grid-form {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn-submit,
        .form-actions .btn-cancel {
            width: 100%;
            justify-content: center;
        }

        .data-table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .data-table thead {
            display: none;
        }

        .data-table tbody tr {
            display: flex;
            flex-direction: column;
            padding: 14px 16px;
            border-bottom: 1px solid var(--glass-border);
            gap: 6px;
        }

        .data-table td {
            padding: 4px 0;
            border-bottom: none;
            font-size: 13px;
        }

        .data-table td::before {
            content: attr(data-label);
            font-weight: 700;
            font-size: 10px;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            display: block;
            margin-bottom: 2px;
        }

        .class-selector {
            overflow-x: auto;
            flex-wrap: nowrap;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-bottom: 4px;
        }
        .class-selector::-webkit-scrollbar { display: none; }

        .class-btn {
            flex-shrink: 0;
        }
    }

    @media (max-width: 480px) {
        .page-header .page-title {
            font-size: 17px;
        }
    }

    /* ══════════════════════════════════════════════
       DASHBOARD STAT ROW
       ══════════════════════════════════════════════ */
    .stat-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-xl);
    }

    .stat-mini {
        background: var(--color-surface);
        border: 1px solid var(--color-hairline);
        padding: 32px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .stat-mini-val {
        font-family: var(--font-family-base);
        font-size: 40px;
        font-weight: 400;
        color: var(--color-ink);
        line-height: 1;
        letter-spacing: var(--tracking-tight);
    }

    .stat-mini-lbl {
        font-size: 12px;
        font-weight: 700;
        color: var(--color-stone);
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .stat-trend { font-size: 11px; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
    .up { color: var(--green); } .dn { color: var(--red); } .nt { color: var(--text3); }

    @media (max-width: 1024px) {
        .stat-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .stat-row {
            grid-template-columns: 1fr;
        }
        .stat-mini {
            padding: 14px 16px;
        }
        .stat-mini-val {
            font-size: 20px;
        }
    }

    /* ══════════════════════════════════════════════
       DASHBOARD GRID
       ══════════════════════════════════════════════ */
    .dash-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 320px;
        grid-template-rows: auto auto;
        gap: 16px;
    }

    @media (max-width: 1200px) {
        .dash-grid {
            grid-template-columns: 1fr 1fr;
        }
        .dash-grid > div[style*="grid-row: span 2"] {
            grid-column: span 2;
        }
        .dash-grid > div[style*="grid-column: span 2"] {
            grid-column: span 2;
        }
    }

    @media (max-width: 768px) {
        .dash-grid {
            grid-template-columns: 1fr;
        }
        .dash-grid > div[style*="grid-row: span 2"],
        .dash-grid > div[style*="grid-column: span 2"] {
            grid-column: 1;
        }
    }

    /* ══════════════════════════════════════════════
       MISC SHARED
       ══════════════════════════════════════════════ */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text3);
    }
    .empty-state i {
        font-size: 40px;
        margin-bottom: 12px;
        display: block;
        opacity: 0.4;
    }
    .empty-state p {
        font-size: 13px;
        max-width: 300px;
        margin: 0 auto;
    }

    /* ══════════════════════════════════════════════
       SCHEDULE GRID
       ══════════════════════════════════════════════ */
    .schedule-container {
        overflow: hidden;
        margin-bottom: 28px;
        box-shadow: var(--shadow-sm);
    }

    .schedule-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--glass);
    }

    .schedule-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text1);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .schedule-title i { color: var(--accent); font-size: 18px; }

    .grid-wrap {
        overflow-x: auto;
        padding: 20px;
        scrollbar-width: thin;
    }

    .visual-grid {
        width: 100%;
        min-width: 900px;
        border-collapse: separate;
        border-spacing: 6px;
        table-layout: fixed;
    }

    .visual-grid th {
        padding: 10px;
        text-align: center;
        font-size: 10.5px;
        font-weight: 700;
        color: var(--text3);
        text-transform: uppercase;
        letter-spacing: 0.12em;
    }

    .visual-grid td {
        height: 85px;
        vertical-align: top;
        position: relative;
    }

    .day-cell {
        width: 100px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: var(--r-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: var(--text1);
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
    }

    .lesson-card {
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        border-radius: var(--r-sm);
        padding: 12px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 4px solid var(--accent);
        cursor: pointer;
        position: relative;
    }

    .lesson-card:hover {
        background: var(--glass-hover);
        border-color: rgba(96,165,250,0.3);
        transform: scale(1.03);
        z-index: 10;
        box-shadow: var(--shadow-lg);
    }

    .mapel-name {
        font-weight: 700;
        font-size: 13px;
        color: var(--text1);
        line-height: 1.3;
        margin-bottom: 5px;
    }

    .guru-name {
        font-size: 11.5px;
        color: var(--text2);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .ruangan-tag {
        font-size: 10px;
        background: var(--accent-soft);
        color: var(--accent);
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 700;
        margin-top: 6px;
        align-self: flex-start;
    }

    .empty-slot {
        background: var(--glass);
        border: 1px dashed var(--glass-border);
        border-radius: var(--r-sm);
        height: 100%;
    }

    .lesson-card.color-1 { border-left-color: var(--accent); }
    .lesson-card.color-2 { border-left-color: var(--purple); }
    .lesson-card.color-3 { border-left-color: var(--green); }
    .lesson-card.color-4 { border-left-color: var(--amber); }
    .lesson-card.color-5 { border-left-color: var(--red); }

    /* ══════════════════════════════════════════════
       WELCOME STRIP
       ══════════════════════════════════════════════ */
    .welcome-strip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .ws-left h2 {
        font-family: 'Outfit', sans-serif;
        font-size: 22px; font-weight: 800;
        color: var(--text1);
        letter-spacing: -0.02em;
    }

    .ws-left p { font-size: 13px; color: var(--text2); margin-top: 3px; }

    .ws-time {
        font-family: 'Outfit', sans-serif;
        font-size: 30px; font-weight: 800;
        color: var(--text1);
        letter-spacing: -0.03em;
        line-height: 1;
    }

    .ws-date {
        text-align: right;
        font-size: 12px;
        color: var(--text2);
    }

    .ws-date strong {
        display: block;
        font-size: 13px;
        color: var(--text1);
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .welcome-strip {
            flex-direction: column;
            align-items: flex-start;
        }
        .ws-left h2 { font-size: 18px; }
        .ws-time { font-size: 24px; }
        .ws-date { text-align: left; }
    }

    /* ══════════════════════════════════════════════
       GLOBAL THEME COMPATIBILITY OVERRIDES
       Fixes hardcoded colors in individual pages
       ══════════════════════════════════════════════ */

    /*
     * Force white text on accent/gradient buttons regardless of page overrides.
     * Many pages use `color: var(--navy)` which breaks in light mode
     * because --navy becomes a light color.
     */
    .btn-primary, .btn-primary:hover,
    .btn-submit, .btn-submit:hover,
    .btn-add, .btn-add:hover,
    .btn-primary-scoola, .btn-primary-scoola:hover {
        color: #fff !important;
    }

    /* Soft buttons: ensure they use var(--accent) consistently */
    .btn-view, .btn-edit, .btn-detail, .btn-print, .btn-icon {
        color: var(--accent) !important;
    }

    /* Override hardcoded hover backgrounds on buttons */
    .btn-primary:hover,
    .btn-add:hover,
    .btn-primary-scoola:hover,
    .btn-submit:hover {
        background: var(--gradient-accent) !important;
        filter: brightness(1.12);
    }

    /* Gradient elements: always white text */
    .avatar-sm, .stat-icon, .sb-avatar {
        color: #fff !important;
    }

    /* Table cell borders — adapt for light mode */
    [data-theme="light"] .data-table td,
    [data-theme="light"] .data-table th,
    [data-theme="light"] .card-toolbar,
    [data-theme="light"] .h-row {
        border-bottom-color: rgba(0,0,0,0.08) !important;
    }

    /* Light mode: ensure hover backgrounds are visible */
    [data-theme="light"] .data-table tbody tr:hover td {
        background: rgba(0,0,0,0.035) !important;
    }

    [data-theme="light"] .stat-card:hover,
    [data-theme="light"] .stat-mini:hover {
        border-color: rgba(59,130,246,0.3) !important;
        background: #fff !important;
    }

    /* Light mode: search box and filter focus */
    [data-theme="light"] .search-box:focus-within,
    [data-theme="light"] .filter-select:focus,
    [data-theme="light"] .form-control:focus,
    [data-theme="light"] .form-select:focus {
        border-color: rgba(59,130,246,0.4) !important;
    }

    /* Light mode text readability for secondary labels */
    [data-theme="light"] .stat-label,
    [data-theme="light"] .info-label,
    [data-theme="light"] .page-subtitle {
        color: #64748b !important;
    }

    /* Fix donut chart stroke in light mode */
    [data-theme="light"] circle[stroke="var(--navy3)"] {
        stroke: rgba(0,0,0,0.08) !important;
    }

    /* Calendar day-today: ensure visible text */
    .cal-day.today {
        color: #fff !important;
    }

    /* Print media: force readable colors (these are intentionally hardcoded for print) */
    @media print {
        .btn-primary, .btn-submit, .btn-add, .btn-cancel,
        .sidebar, .topbar, .hamburger-btn,
        .sidebar-overlay, .toolbar-right { display: none !important; }
        .main-wrapper { margin-left: 0 !important; }
        body { background: #fff; color: #333; }
        .card, .info-card, .donut-card, .stat-card, .form-card {
            border: 1px solid #ddd;
            background: #fff;
            box-shadow: none;
        }
        .data-table th, .data-table td {
            color: #333 !important;
            border-color: #ddd;
        }
        .page-title, .card-label, .cell-name, .stat-value,
        .info-value, .donut-pct, .legend-count {
            color: #333 !important;
        }
    }

    /* ══════════════════════════════════════════════
       BOOTSTRAP OVERRIDE — Force Scoola theme vars
       This ensures all text/input colors follow the
       Scoola design system in both Light & Dark mode.
       ══════════════════════════════════════════════ */

    /* Base text color override */
    body,
    .main-wrapper,
    .page-body,
    p, span, div, li, td, th, label, a {
        color: var(--text1);
    }

    /* Muted/secondary text */
    .text-muted,
    small,
    .form-text {
        color: var(--text2) !important;
    }

    /* Headings */
    h1, h2, h3, h4, h5, h6 {
        color: var(--text1);
    }

    /* Links */
    a {
        color: var(--accent);
        text-decoration: none;
    }
    a:hover {
        color: var(--accent-light);
    }

    /* Form inputs — critical Bootstrap override */
    input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="submit"]):not([type="button"]),
    textarea,
    select,
    .form-control,
    .form-select {
        background-color: var(--input-bg) !important;
        color: var(--text1) !important;
        border-color: var(--glass-border) !important;
    }

    input::placeholder,
    textarea::placeholder {
        color: var(--text3) !important;
    }

    input:focus,
    textarea:focus,
    select:focus,
    .form-control:focus,
    .form-select:focus {
        background-color: var(--navy3) !important;
        color: var(--text1) !important;
        border-color: var(--accent-glow) !important;
        box-shadow: 0 0 0 3px rgba(124,58,237,0.1) !important;
    }

    /* Table cells */
    .table, .table th, .table td,
    table th, table td {
        color: var(--text1);
        border-color: var(--glass-border) !important;
    }

    /* Dropdown / Select option */
    option {
        background: var(--navy2-solid);
        color: var(--text1);
    }

    /* Bootstrap card override */
    .card {
        background: var(--navy2);
        border-color: var(--glass-border);
        color: var(--text1);
    }

    /* Bootstrap modal */
    .modal-content {
        background: var(--navy2-solid);
        color: var(--text1);
        border-color: var(--glass-border);
    }

    /* Bootstrap badge text */
    .badge {
        color: inherit;
    }

    /* Autofill override for all browsers */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    textarea:-webkit-autofill,
    select:-webkit-autofill {
        -webkit-text-fill-color: var(--text1) !important;
        -webkit-box-shadow: 0 0 0px 1000px var(--navy3) inset !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    /* Alert override */
    .alert {
        color: var(--text1);
    }

    /* Button text (non-primary) */
    .btn {
        color: var(--text1);
    }
    .btn-primary, .btn-success, .btn-danger, .btn-warning, .btn-info {
        color: #fff;
    }

</style>

