{{-- Shared Component Styles — Used by all layouts --}}
<style>
    /* ══════════════════════════════════════════════
       PAGE HEADER
       ══════════════════════════════════════════════ */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 28px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .page-header-left .page-title,
    .page-header .page-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: var(--text1);
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .page-header-left .page-subtitle,
    .page-header .page-subtitle {
        font-size: 13px;
        color: var(--text2);
        margin-top: 4px;
    }

    /* ══════════════════════════════════════════════
       CARDS
       ══════════════════════════════════════════════ */
    .card,
    .form-card,
    .schedule-container,
    .list-card {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        transition: border-color var(--transition), box-shadow var(--transition);
    }

    .card:hover { border-color: rgba(96,165,250,0.15); }

    .card-inner { padding: 20px 22px; }

    .card-title {
        font-size: 13px; font-weight: 700;
        color: var(--text1);
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 16px;
        letter-spacing: -0.01em;
    }

    .card-title-icon { font-size: 14px; }
    .card-title-action {
        margin-left: auto;
        font-size: 11.5px;
        color: var(--accent);
        cursor: pointer;
        font-weight: 500;
        text-decoration: none;
        transition: color var(--transition);
    }
    .card-title-action:hover { color: var(--text1); }

    /* ══════════════════════════════════════════════
       BUTTONS
       ══════════════════════════════════════════════ */
    .btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 20px;
        background: var(--gradient-accent);
        color: #fff;
        border: none; border-radius: var(--r-sm);
        font-size: 13px; font-weight: 700;
        text-decoration: none;
        transition: all var(--transition);
        white-space: nowrap;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        box-shadow: 0 2px 8px rgba(96,165,250,0.25);
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(96,165,250,0.4);
        color: #fff;
    }
    .btn-primary:active { transform: translateY(0); }

    .btn-submit {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 22px;
        background: var(--gradient-accent);
        color: #fff;
        border: none; border-radius: var(--r-sm);
        font-size: 13px; font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all var(--transition);
        box-shadow: 0 2px 8px rgba(96,165,250,0.25);
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(96,165,250,0.4);
    }
    .btn-submit:active { transform: translateY(0); }

    .btn-cancel {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 10px 22px;
        background: var(--navy3);
        border: 1px solid var(--glass-border);
        color: var(--text2);
        border-radius: var(--r-sm);
        font-size: 13px; font-weight: 600;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        transition: all var(--transition);
        cursor: pointer;
    }
    .btn-cancel:hover {
        background: var(--glass-hover);
        color: var(--text1);
        border-color: var(--text3);
    }

    .btn-edit {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 14px;
        background: var(--accent-soft);
        border: 1px solid rgba(96,165,250,0.15);
        color: var(--accent);
        border-radius: var(--r-xs);
        font-size: 12px; font-weight: 600;
        text-decoration: none;
        transition: all var(--transition);
        cursor: pointer;
        font-family: 'Inter', sans-serif;
    }
    .btn-edit:hover {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
        transform: translateY(-1px);
    }

    .btn-danger {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 14px;
        background: var(--red-soft);
        border: 1px solid rgba(248,113,113,0.15);
        color: var(--red);
        border-radius: var(--r-xs);
        font-size: 12px; font-weight: 600;
        text-decoration: none;
        transition: all var(--transition);
        cursor: pointer;
        font-family: 'Inter', sans-serif;
    }
    .btn-danger:hover {
        background: var(--red);
        color: #fff;
        border-color: var(--red);
    }

    .btn-back {
        display: inline-flex; align-items: center; gap: 7px;
        color: var(--text3); font-size: 12.5px; text-decoration: none;
        margin-bottom: 18px; transition: color var(--transition); font-weight: 500;
    }
    .btn-back:hover { color: var(--accent); }

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
        font-size: 11.5px;
        font-weight: 700;
        color: var(--text2);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: .07em;
    }

    .form-control,
    .form-select {
        width: 100%;
        background: var(--input-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--r-xs);
        padding: 11px 16px;
        color: var(--text1);
        font-size: 13.5px;
        font-family: 'Inter', sans-serif;
        transition: all var(--transition);
        outline: none;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--accent-glow);
        box-shadow: 0 0 0 3px rgba(96,165,250,0.1);
        background: var(--navy3);
    }

    .form-control::placeholder { color: var(--text3); }

    select.form-control,
    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 12px 12px;
        padding-right: 42px;
        cursor: pointer;
    }

    select.form-control option,
    .form-select option {
        background: #1e293b;
        color: #e2e8f0;
        padding: 10px 14px;
        font-size: 13px;
    }

    select.form-control option:checked,
    .form-select option:checked {
        background: #334155;
        color: #60a5fa;
    }

    select.form-control option:disabled,
    .form-select option:disabled {
        color: #64748b;
    }

    [data-theme="light"] select.form-control,
    [data-theme="light"] .form-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
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
        padding: 14px 20px;
        background: var(--glass);
        font-size: 11px;
        font-weight: 700;
        color: var(--text3);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        text-align: left;
        border-bottom: 1px solid var(--glass-border);
    }

    .data-table td {
        padding: 14px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.03);
        color: var(--text2);
        font-size: 13px;
        transition: all var(--transition);
    }

    .data-table tr:hover td {
        background: var(--glass-hover);
        color: var(--text1);
    }

    .data-table tr:last-child td { border-bottom: none; }

    [data-theme="light"] .data-table td {
        border-bottom-color: rgba(0,0,0,0.04);
    }

    /* ══════════════════════════════════════════════
       BADGES
       ══════════════════════════════════════════════ */
    .badge-status {
        font-size: 11px; font-weight: 600;
        padding: 3px 10px; border-radius: 20px;
        display: inline-block;
        letter-spacing: 0.02em;
    }

    .bs-h { background: var(--green-soft);  color: var(--green); }
    .bs-i { background: var(--amber-soft);  color: var(--amber); }
    .bs-a { background: var(--red-soft);    color: var(--red); }
    .bs-s { background: var(--purple-soft); color: var(--purple); }

    .hari-badge {
        background: var(--accent-soft);
        color: var(--accent);
        padding: 4px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 12px;
        border: 1px solid rgba(96,165,250,0.12);
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
        border: 1px solid rgba(96,165,250,0.12);
        color: var(--accent);
        padding: 9px 16px;
        border-radius: var(--r-xs);
        font-family: 'Plus Jakarta Sans', sans-serif;
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
        box-shadow: 0 3px 12px rgba(96,165,250,0.3);
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
        gap: 14px;
        margin-bottom: 18px;
    }

    .stat-mini {
        background: var(--navy2);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all var(--transition);
    }

    .stat-mini:hover {
        border-color: rgba(96,165,250,0.2);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-mini-icon {
        width: 42px; height: 42px;
        border-radius: var(--r-sm);
        display: grid; place-items: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .ic-blue   { background: var(--accent-soft); color: var(--accent); }
    .ic-green  { background: var(--green-soft);  color: var(--green); }
    .ic-amber  { background: var(--amber-soft);  color: var(--amber); }
    .ic-red    { background: var(--red-soft);    color: var(--red); }
    .ic-purple { background: var(--purple-soft); color: var(--purple); }

    .stat-mini-val {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 24px; font-weight: 800;
        color: var(--text1);
        line-height: 1;
        letter-spacing: -0.02em;
    }

    .stat-mini-lbl { font-size: 12px; color: var(--text2); margin-top: 3px; }

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
        font-family: 'Plus Jakarta Sans', sans-serif;
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
        border: 1px dashed rgba(255,255,255,0.06);
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
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 22px; font-weight: 800;
        color: var(--text1);
        letter-spacing: -0.02em;
    }

    .ws-left p { font-size: 13px; color: var(--text2); margin-top: 3px; }

    .ws-time {
        font-family: 'Plus Jakarta Sans', sans-serif;
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
</style>
