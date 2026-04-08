<!DOCTYPE html>
<html lang="id" data-theme="light">
<script>
    // Apply saved theme BEFORE render (prevents flash)
    (function() {
        var t = localStorage.getItem('scoola-theme') || 'light';
        document.documentElement.setAttribute('data-theme', t);
    })();
</script>
<head>
    <meta charset="UTF-8">
    <title>PresensiDigital — Masuk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Bebas+Neue&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>

    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    /* ════════════════════════════
       TOKENS — LIGHT (default)
    ════════════════════════════ */
    :root, [data-theme="light"] {
        --lp-bg:        #eeeae3;
        --lp-bg2:       #e5e0d8;
        --lp-text:      #1c1c1c;
        --lp-sub:       rgba(28,28,28,0.42);
        --lp-card:      rgba(255,255,255,0.45);
        --lp-card-bdr:  rgba(255,255,255,0.7);
        --lp-bar-bg:    rgba(0,0,0,0.09);
        --lp-bar-a:     #2a2a2a;
        --lp-bar-b:     rgba(0,0,0,0.2);
        --lp-badge-bg:  rgba(255,255,255,0.55);
        --lp-badge-bdr: rgba(255,255,255,0.8);

        --rp-bg:        #dbdbdb;
        --rp-border:    rgba(0,0,0,0.06);
        --tx:           #111111;
        --tx-sub:       #000000;
        --tx-hint:      #bbbbbb;
        --in-bg:        #f7f7f7;
        --in-bdr:       #e8e8e8;
        --in-focus-bdr: #111111;
        --in-focus-sh:  rgba(17,17,17,0.07);
        --btn-bg:       #111111;
        --btn-tx:       #ffffff;
        --btn-sh:       rgba(17,17,17,0.18);
        --btn-sh2:      rgba(17,17,17,0.28);
        --sso-bdr:      #ebebeb;
        --sso-hover:    #f5f5f5;
        --div-c:        #efefef;
        --foot-c:       #c8c8c8;
        --tog-bg:       #111111;
        --tog-tx:       #ffffff;
        --tog-thumb:    #ffffff;
        --tog-track:    rgba(0,0,0,0.15);

        --orb-hex: 0xffffff;
        --wire-hex: 0x2a2a2a;
        --pt-hex: 0x555555;
        --glow-hex: 0xffffff;
    }

    /* ════════════════════════════
       TOKENS — DARK
    ════════════════════════════ */
    [data-theme="dark"] {
        --lp-bg:        #0c0c0c;
        --lp-bg2:       #111111;
        --lp-text:      #f0f0f0;
        --lp-sub:       rgba(240,240,240,0.3);
        --lp-card:      rgba(255,255,255,0.04);
        --lp-card-bdr:  rgba(255,255,255,0.1);
        --lp-bar-bg:    rgba(255,255,255,0.07);
        --lp-bar-a:     #ffffff;
        --lp-bar-b:     rgba(255,255,255,0.3);
        --lp-badge-bg:  rgba(255,255,255,0.06);
        --lp-badge-bdr: rgba(255,255,255,0.12);

        --rp-bg:        #141414;
        --rp-border:    rgba(255,255,255,0.06);
        --tx:           #f0f0f0;
        --tx-sub:       #d8d8d8;
        --tx-hint:      #444444;
        --in-bg:        rgba(255,255,255,0.04);
        --in-bdr:       rgba(255,255,255,0.1);
        --in-focus-bdr: rgba(255,255,255,0.5);
        --in-focus-sh:  rgba(255,255,255,0.04);
        --btn-bg:       #f0f0f0;
        --btn-tx:       #111111;
        --btn-sh:       rgba(240,240,240,0.08);
        --btn-sh2:      rgba(240,240,240,0.15);
        --sso-bdr:      rgba(255,255,255,0.09);
        --sso-hover:    rgba(255,255,255,0.05);
        --div-c:        rgba(255,255,255,0.07);
        --foot-c:       #3a3a3a;
        --tog-bg:       #f0f0f0;
        --tog-tx:       #111111;
        --tog-thumb:    #111111;
        --tog-track:    rgba(255,255,255,0.18);

        --orb-hex: 0x111111;
        --wire-hex: 0xffffff;
        --pt-hex: 0xffffff;
        --glow-hex: 0xffffff;
    }

    html, body { height: 100%; font-family: 'DM Sans', sans-serif; overflow: hidden; }

    /* ── TOGGLE BUTTON ── */
    .theme-btn {
        position: fixed; top: 22px; left: 22px; z-index: 9999;
        display: flex; align-items: center; gap: 9px;
        padding: 8px 14px 8px 11px;
        border-radius: 40px; border: none; cursor: pointer;
        background: var(--tog-bg); color: var(--tog-tx);
        font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 600;
        letter-spacing: 0.03em;
        box-shadow: 0 2px 12px rgba(0,0,0,0.13), 0 0 0 1px rgba(0,0,0,0.06);
        transition: background .35s, color .35s, box-shadow .25s, transform .15s;
        user-select: none;
    }
    .theme-btn:hover  { transform: translateY(-1px); box-shadow: 0 5px 18px rgba(0,0,0,0.18); }
    .theme-btn:active { transform: scale(0.96); }

    .tb-track {
        width: 32px; height: 18px; border-radius: 40px;
        background: var(--tog-track); position: relative; flex-shrink: 0;
        transition: background .35s;
    }
    .tb-thumb {
        position: absolute; top: 2px; left: 2px;
        width: 14px; height: 14px; border-radius: 50%;
        background: var(--tog-thumb);
        transition: transform .32s cubic-bezier(.34,1.56,.64,1), background .35s;
    }
    [data-theme="dark"] .tb-thumb { transform: translateX(14px); }

    .icon-moon { display: flex; }
    .icon-sun  { display: none; }
    [data-theme="dark"] .icon-moon { display: none; }
    [data-theme="dark"] .icon-sun  { display: flex; }

    /* ── LAYOUT ── */
    .wrapper {
        display: grid; grid-template-columns: 1.05fr 0.95fr;
        height: 100vh; width: 100vw;
    }

    /* ════════════════════════════
       LEFT PANEL
    ════════════════════════════ */
    .lp {
        background: var(--lp-bg);
        position: relative; overflow: hidden;
        display: flex; flex-direction: column;
        justify-content: space-between;
        padding: 32px 40px 28px;
        transition: background .4s;
    }

    /* Subtle gradient overlay */
    .lp::after {
        content: '';
        position: absolute; inset: 0; z-index: 1; pointer-events: none;
        background: radial-gradient(ellipse 80% 70% at 50% 55%, transparent 40%, rgba(0,0,0,0.06) 100%);
        transition: opacity .4s;
    }
    [data-theme="dark"] .lp::after {
        background: radial-gradient(ellipse 80% 70% at 50% 55%, transparent 40%, rgba(0,0,0,0.45) 100%);
    }

    #three-canvas { position: absolute; inset: 0; z-index: 1; }

    /* TOP BAR */
    .lp-top {
        position: relative; z-index: 3;
        display: flex; align-items: center; justify-content: space-between;
        opacity: 0; animation: fadeUp .55s ease forwards .15s;
    }

    .brand {
        display: flex; align-items: center; gap: 9px;
    }
    .brand-ring {
        width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
        position: relative;
        background: conic-gradient(from 0deg, #c8c0b8, #e8e4df, #c8c0b8);
        box-shadow: 0 0 0 2px rgba(255,255,255,0.6), 0 2px 8px rgba(0,0,0,0.12);
        transition: box-shadow .4s;
    }
    [data-theme="dark"] .brand-ring {
        background: conic-gradient(from 0deg, #888, #fff, #888);
        box-shadow: 0 0 0 2px rgba(255,255,255,0.15), 0 2px 8px rgba(0,0,0,0.4);
    }
    .brand-ring::after {
        content: '';
        position: absolute; inset: 5px; border-radius: 50%;
        background: var(--lp-bg);
        transition: background .4s;
    }
    .brand-name {
        font-size: 14px; font-weight: 700; color: var(--lp-text);
        letter-spacing: 0.01em; transition: color .4s;
    }

    .lp-tagline {
        font-size: 11px; color: var(--lp-sub);
        letter-spacing: 0.04em; transition: color .4s;
    }

    /* HERO TEXT */
    .lp-hero {
        position: relative; z-index: 3; text-align: center;
        pointer-events: none;
        opacity: 0; animation: fadeUp .75s ease forwards .45s;
    }
    .lp-hero h1 {
        font-family: 'Instrument Serif', serif;
        font-size: clamp(2.6rem, 3.4vw, 3.8rem);
        font-weight: 400; color: var(--lp-text); line-height: 1.1;
        transition: color .4s;
    }
    .lp-hero h1 em {
        font-style: italic; color: var(--lp-sub); transition: color .4s;
    }
    .lp-hero p {
        margin-top: 10px; font-size: 13px; color: var(--lp-sub);
        letter-spacing: 0.02em; transition: color .4s;
    }

    /* STAT CARD */
    .stat-card {
        position: absolute; z-index: 3;
        bottom: 108px; left: 50%; transform: translateX(-50%);
        width: 270px;
        background: var(--lp-card);
        border: 1px solid var(--lp-card-bdr);
        backdrop-filter: blur(24px) saturate(1.4);
        -webkit-backdrop-filter: blur(24px) saturate(1.4);
        border-radius: 20px; padding: 20px 22px 18px;
        opacity: 0;
        animation: floatCard 4s ease-in-out infinite, fadeUp .8s ease forwards .85s;
        transition: background .4s, border-color .4s;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    }
    [data-theme="dark"] .stat-card {
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    }

    @keyframes floatCard {
        0%,100% { transform: translateX(-50%) translateY(0px); }
        50%      { transform: translateX(-50%) translateY(-9px); }
    }

    .sc-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 12px;
    }
    .sc-label {
        font-size: 10px; font-weight: 600; color: var(--lp-sub);
        letter-spacing: 0.1em; text-transform: uppercase; transition: color .4s;
    }
    .sc-badge {
        font-size: 10px; font-weight: 600; color: #16a34a;
        background: rgba(22,163,74,0.1); border-radius: 20px;
        padding: 2px 8px; letter-spacing: 0.04em;
    }
    .sc-value {
        font-size: 32px; font-weight: 800; color: var(--lp-text);
        line-height: 1; letter-spacing: -0.02em;
        margin-bottom: 14px; transition: color .4s;
    }
    .sc-value span { font-size: 14px; font-weight: 500; color: var(--lp-sub); margin-left: 4px; }

    .bars {
        display: flex; gap: 5px; align-items: flex-end; height: 38px;
    }
    .bar { flex: 1; border-radius: 4px; background: var(--lp-bar-bg); position: relative; overflow: hidden; transition: background .4s; }
    .bar-fill {
        position: absolute; bottom: 0; left: 0; right: 0; border-radius: 4px;
        background: linear-gradient(to top, var(--lp-bar-a), var(--lp-bar-b));
        transform: scaleY(0); transform-origin: bottom;
        animation: growBar 1.6s cubic-bezier(.22,1,.36,1) forwards;
        transition: background .4s;
    }
    @keyframes growBar { to { transform: scaleY(1); } }

    /* BOTTOM ROW */
    .lp-bottom {
        position: relative; z-index: 3;
        display: flex; align-items: center; justify-content: space-between;
        opacity: 0; animation: fadeUp .5s ease forwards .95s;
    }
    .lp-bottom-copy {
        font-size: 11px; color: var(--lp-sub); transition: color .4s;
    }
    .lp-dots {
        display: flex; gap: 5px;
    }
    .lp-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--lp-sub); opacity: 0.4; transition: background .4s;
    }
    .lp-dot:first-child { opacity: 1; }

    /* ════════════════════════════
       RIGHT PANEL
    ════════════════════════════ */
    .rp {
        background: var(--rp-bg);
        border-left: 1px solid var(--rp-border);
        display: flex; flex-direction: column;
        justify-content: space-between;
        padding: 28px 52px 28px 52px;
        overflow-y: auto;
        transition: background .4s, border-color .4s;
    }

    .rp-top {
        display: flex; justify-content: flex-end; align-items: center;
        opacity: 0; animation: fadeUp .5s ease forwards .2s;
    }
    .rp-top-inner {
        display: flex; align-items: center; gap: 14px;
    }
    .rp-top-text {
        font-size: 12.5px; color: var(--tx-sub); transition: color .4s;
    }
    .rp-top-link {
        display: flex; align-items: center; gap: 6px;
        font-size: 12.5px; font-weight: 600; color: var(--tx);
        text-decoration: none; padding: 7px 14px;
        border: 1.5px solid var(--in-bdr);
        border-radius: 40px;
        transition: background .2s, border-color .2s, color .4s;
    }
    .rp-top-link:hover { background: var(--sso-hover); }

    /* FORM */
    .form-wrap {
        flex: 1; display: flex; flex-direction: column;
        justify-content: center;
        max-width: 360px; margin: 0 auto; width: 100%;
    }

    .form-eyebrow {
        font-size: 11px; font-weight: 600; color: var(--tx-sub);
        letter-spacing: 0.1em; text-transform: uppercase;
        margin-bottom: 10px;
        opacity: 0; animation: fadeUp .5s ease forwards .32s;
        transition: color .4s;
    }

    .form-title {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 4.4rem; font-weight: 400;
        letter-spacing: 0.05em; color: var(--tx); line-height: 0.95;
        margin-bottom: 8px;
        opacity: 0; animation: fadeUp .55s ease forwards .38s;
        transition: color .4s;
    }

    .form-sub {
        font-size: 13px; color: var(--tx-sub); margin-bottom: 30px;
        opacity: 0; animation: fadeUp .5s ease forwards .44s;
        transition: color .4s;
    }

    /* Fields */
    .field {
        margin-bottom: 12px;
        opacity: 0; animation: fadeUp .5s ease forwards var(--d, .52s);
    }
    .field-label {
        display: block; font-size: 11.5px; font-weight: 600;
        color: var(--tx-sub); margin-bottom: 6px;
        letter-spacing: 0.03em; transition: color .4s;
    }
    .field-inner { position: relative; }
    .field input {
        width: 100%; padding: 13px 16px;
        border: 1.5px solid var(--in-bdr);
        border-radius: 12px;
        font-family: 'DM Sans', sans-serif; font-size: 14px;
        color: var(--tx); background: var(--in-bg);
        outline: none;
        transition: border-color .2s, box-shadow .2s, background .35s, color .35s;
    }
    .field input::placeholder { color: var(--tx-hint); }
    .field input:focus {
        border-color: var(--in-focus-bdr);
        box-shadow: 0 0 0 3.5px var(--in-focus-sh);
    }
    .field-pw input { padding-right: 46px; }

    .error-text {
        font-size: 11.5px;
        color: #dc2626;
        margin-top: 6px;
        font-weight: 500;
        display: block;
        letter-spacing: 0.02em;
    }
    [data-theme="dark"] .error-text { color: #f87171; }
    .has-error { border-color: #dc2626 !important; }
    [data-theme="dark"] .has-error { border-color: #f87171 !important; }

    .eye-btn {
        position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
        background: none; border: none; cursor: pointer;
        color: var(--tx-hint); display: flex; padding: 2px;
        transition: color .2s;
    }
    .eye-btn:hover { color: var(--tx); }

    .field-footer {
        display: flex; justify-content: flex-end; margin-top: 5px;
        opacity: 0; animation: fadeUp .5s ease forwards .62s;
    }
    .forgot { font-size: 12.5px; color: var(--tx-sub); text-decoration: none; transition: color .2s; }
    .forgot:hover { color: var(--tx); }

    /* Sign-in button */
    .btn-main {
        width: 100%; padding: 14px 20px; margin-top: 20px;
        border: none; border-radius: 50px;
        background: var(--btn-bg); color: var(--btn-tx);
        font-family: 'DM Sans', sans-serif;
        font-size: 14.5px; font-weight: 700; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 9px;
        box-shadow: 0 3px 16px var(--btn-sh);
        transition: transform .2s, box-shadow .2s, background .35s, color .35s;
        letter-spacing: 0.02em;
        opacity: 0; animation: fadeUp .5s ease forwards .7s;
    }
    .btn-main:hover  { transform: translateY(-2px); box-shadow: 0 7px 26px var(--btn-sh2); }
    .btn-main:active { transform: translateY(0) scale(0.98); }

    /* Divider */
    .divider {
        display: flex; align-items: center; gap: 12px; margin: 20px 0 16px;
        opacity: 0; animation: fadeUp .5s ease forwards .78s;
    }
    .divider hr { flex: 1; border: none; border-top: 1px solid var(--div-c); transition: border-color .4s; }
    .divider span { font-size: 11.5px; color: var(--tx-hint); white-space: nowrap; transition: color .4s; }

    /* SSO */
    .sso-row {
        display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
        opacity: 0; animation: fadeUp .5s ease forwards .84s;
    }
    .sso-btn {
        padding: 10px; border: 1.5px solid var(--sso-bdr); border-radius: 11px;
        background: transparent; font-family: 'DM Sans', sans-serif;
        font-size: 13px; font-weight: 500; color: var(--tx); cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 7px;
        transition: border-color .2s, background .2s, color .35s;
    }
    .sso-btn:hover { background: var(--sso-hover); border-color: var(--in-focus-bdr); }
    .gh-icon { fill: var(--tx); transition: fill .4s; }

    /* Footer */
    .rp-bottom {
        text-align: center; font-size: 11px; color: var(--foot-c);
        opacity: 0; animation: fadeUp .5s ease forwards .9s;
        transition: color .4s;
        display: flex; align-items: center; justify-content: center; gap: 4px; flex-wrap: wrap;
    }
    .rp-bottom a { color: var(--tx-hint); text-decoration: none; padding: 0 4px; transition: color .2s; }
    .rp-bottom a:hover { color: var(--tx); }
    .rp-bottom .sep { color: var(--div-c); }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 820px) {
        .wrapper { grid-template-columns: 1fr; }
        .lp { display: none; }
        html, body { overflow: auto; }
        .rp { padding: 28px 32px; }
    }
    </style>
</head>
<body>

<!-- ── THEME TOGGLE ── -->
<button class="theme-btn" onclick="toggleTheme()" id="themeBtn">
    <svg class="icon-moon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
    </svg>
    <svg class="icon-sun" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="5"/>
        <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
        <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
    </svg>
    <div class="tb-track"><div class="tb-thumb"></div></div>
    <span id="togLabel">Dark</span>
</button>

<div class="wrapper">

    <!-- ══ LEFT PANEL ══ -->
    <div class="lp">
        <canvas id="three-canvas"></canvas>

        <div class="lp-top">
            <div class="brand">
                <div class="brand-ring"></div>
                <span class="brand-name">Presensi Digital</span>
            </div>
            <span class="lp-tagline">v2.0 · 2025</span>
        </div>

        <div class="lp-hero">
            <h1>Catat<br><em>kehadiran</em><br>dengan mudah</h1>
            <p>Sistem Presensi digital modern & akurat</p>
        </div>


        <div class="lp-bottom">
            <span class="lp-bottom-copy">© 2025 PresensiDigital</span>
            <div class="lp-dots">
                <div class="lp-dot"></div>
                <div class="lp-dot"></div>
                <div class="lp-dot"></div>
            </div>
        </div>
    </div>

    <!-- ══ RIGHT PANEL ══ -->
    <div class="rp">

        <div class="rp-top">
            <div class="rp-top-inner">
                <span class="rp-top-text">Belum punya akun?</span>
                <a class="rp-top-link" href="#">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                    Ajukan Pembuatan Akun
                </a>
            </div>
        </div>

        <div class="form-wrap">
            <p class="form-eyebrow">Selamat datang kembali</p>
            <h2 class="form-title">MASUK</h2>
            <p class="form-sub">Silakan masuk untuk melanjutkan ke dashboard Anda.</p>

            <form method="POST" action="/login" autocomplete="on">
                @csrf

                <div class="field" style="--d:.52s">
                    <label class="field-label" for="emailInput">Email</label>
                    <div class="field-inner">
                        <input type="email" id="emailInput" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" class="{{ $errors->has('email') ? 'has-error' : '' }}">
                    </div>
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field" style="--d:.59s">
                    <label class="field-label" for="pwInput">Password</label>
                    <div class="field-inner field-pw">
                        <input type="password" id="pwInput" name="password" placeholder="••••••••" required autocomplete="current-password" class="{{ $errors->has('password') ? 'has-error' : '' }}">
                        <button type="button" class="eye-btn" onclick="togglePw()" title="Tampilkan password">
                            <svg id="pwEye" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field-footer">
                    <a href="#" class="forgot">Lupa password?</a>
                </div>

                <button class="btn-main" type="submit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    Masuk Sekarang
                </button>
            </form>

            <div class="divider">
                <hr><span>atau masuk dengan</span><hr>
            </div>

            <div class="sso-row">
                <button class="sso-btn" type="button">
                    <svg width="14" height="14" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Google
                </button>
                <button class="sso-btn" type="button">
                    <svg width="14" height="14" viewBox="0 0 24 24" class="gh-icon">
                        <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                    </svg>
                    GitHub
                </button>
            </div>
        </div>

        <div class="rp-bottom">
            <span>© 2025 PresensiDigital Inc.</span>
            <span class="sep">·</span>
            <a href="#">Bantuan</a>
            <span class="sep">·</span>
            <a href="#">Privasi</a>
            <span class="sep">·</span>
            <a href="#">Bahasa Indonesia ↓</a>
        </div>

    </div>
</div>

<script>
// ── THEME ENGINE (shared with admin via localStorage) ──
const THEME_KEY = 'scoola-theme';

function applyTheme3D(isDark) {
    if (typeof orbMat === 'undefined') return;
    if (isDark) {
        orbMat.color.setHex(0x0f0f0f);
        orbMat.emissiveIntensity = 0.02;
        wireMat.color.setHex(0xffffff);
        pMat.color.setHex(0xdddddd);
        glowMat.color.setHex(0xffffff);
        ambLight.intensity = 0.5;
        ptLight.intensity  = 1.4;
    } else {
        orbMat.color.setHex(0xffffff);
        orbMat.emissiveIntensity = 0.0;
        wireMat.color.setHex(0x2a2a2a);
        pMat.color.setHex(0x555555);
        glowMat.color.setHex(0xffffff);
        ambLight.intensity = 1.1;
        ptLight.intensity  = 0.6;
    }
}

function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme') || 'light';
    const next    = current === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem(THEME_KEY, next);
    document.getElementById('togLabel').textContent = next === 'dark' ? 'Light' : 'Dark';
    applyTheme3D(next === 'dark');
}

// Sync button label on load
(function() {
    const t = localStorage.getItem(THEME_KEY) || 'light';
    document.getElementById('togLabel').textContent = t === 'dark' ? 'Light' : 'Dark';
    // 3D sync happens after THREE is initialized
})();


// ── THREE.JS ──
const canvas = document.getElementById('three-canvas');
const lp     = canvas.parentElement;

const scene  = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(58, lp.clientWidth / lp.clientHeight, 0.1, 100);
camera.position.z = 5;

const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
renderer.setSize(lp.clientWidth, lp.clientHeight);
renderer.setPixelRatio(Math.min(devicePixelRatio, 2));

// Sphere — bright white for light mode
const orbMat = new THREE.MeshStandardMaterial({
    color: 0xffffff,
    roughness: 0.15,
    metalness: 0.65,
    emissive: 0xffffff,
    emissiveIntensity: 0.0
});
const orb = new THREE.Mesh(new THREE.SphereGeometry(1.55, 64, 64), orbMat);
scene.add(orb);

// Wireframe
const wireMat = new THREE.MeshBasicMaterial({ color: 0x2a2a2a, wireframe: true, transparent: true, opacity: 0.7 });
const wire    = new THREE.Mesh(new THREE.SphereGeometry(1.585, 30, 30), wireMat);
scene.add(wire);

// Glow spot
const glowMat = new THREE.MeshBasicMaterial({ color: 0xffffff });
const glow    = new THREE.Mesh(new THREE.SphereGeometry(0.2, 16, 16), glowMat);
glow.position.set(-0.65, 0.55, 1.35);
scene.add(glow);

// Particles
const N = 280, pos = new Float32Array(N * 3);
for (let i = 0; i < N; i++) {
    const r  = 2.6 + Math.random() * 1.7;
    const th = Math.random() * Math.PI * 2;
    const ph = Math.acos(2 * Math.random() - 1);
    pos[i*3]   = r * Math.sin(ph) * Math.cos(th);
    pos[i*3+1] = r * Math.sin(ph) * Math.sin(th);
    pos[i*3+2] = r * Math.cos(ph);
}
const pGeo = new THREE.BufferGeometry();
pGeo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
const pMat = new THREE.PointsMaterial({ color: 0x555555, size: 0.05, transparent: true, opacity: 0.7, sizeAttenuation: true });
const pts  = new THREE.Points(pGeo, pMat);
scene.add(pts);

// Lights
const ambLight = new THREE.AmbientLight(0xffffff, 1.1);
scene.add(ambLight);
const ptLight = new THREE.PointLight(0xffffff, 0.6, 18);
ptLight.position.set(-3, 3, 4);
scene.add(ptLight);
const ptLight2 = new THREE.PointLight(0xffffff, 0.3, 12);
ptLight2.position.set(3, -2, 2);
scene.add(ptLight2);

let t = 0;
(function tick() {
    requestAnimationFrame(tick);
    t += 0.003;
    orb.rotation.y   += 0.003;
    wire.rotation.y  += 0.003;
    wire.rotation.x  += 0.0009;
    pts.rotation.y   += 0.0012;
    pts.rotation.x   += 0.0005;
    orb.position.y   = Math.sin(t) * 0.1;
    wire.position.y  = orb.position.y;
    glow.position.y  = orb.position.y + 0.55;
    renderer.render(scene, camera);
})();

window.addEventListener('resize', () => {
    renderer.setSize(lp.clientWidth, lp.clientHeight);
    camera.aspect = lp.clientWidth / lp.clientHeight;
    camera.updateProjectionMatrix();
});

// ── Password toggle ──
function togglePw() {
    const inp = document.getElementById('pwInput');
    const eye = document.getElementById('pwEye');
    const isHidden = inp.type === 'password';
    inp.type = isHidden ? 'text' : 'password';
    eye.innerHTML = isHidden
        ? `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`
        : `<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;
}
</script>

</body>
</html>