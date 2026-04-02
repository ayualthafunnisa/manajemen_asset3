<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AsetKu — Sistem Manajemen Aset Modern</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --ink: #0d0d14;
      --ink-muted: #4a4a5e;
      --ink-faint: #9898ac;
      --surface: #f7f7fb;
      --white: #ffffff;
      --accent: #5c4aff;
      --accent-light: #ede9ff;
      --accent-glow: rgba(92,74,255,0.18);
      --teal: #00c9b1;
      --amber: #ffb547;
      --rose: #ff5e7d;
      --radius: 20px;
      --radius-sm: 10px;
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--surface);
      color: var(--ink);
      overflow-x: hidden;
    }

    /* ─── NAV ─────────────────────────────── */
    nav {
      position: fixed; top: 0; left: 0; right: 0; z-index: 100;
      padding: 18px 6vw;
      display: flex; align-items: center; justify-content: space-between;
      background: rgba(247,247,251,0.92);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(92,74,255,0.08);
    }
    .nav-logo {
      font-family: 'Syne', sans-serif;
      font-weight: 800; font-size: 1.3rem;
      color: var(--ink);
      display: flex; align-items: center; gap: 10px;
      text-decoration: none;
    }
    .nav-logo .dot {
      width: 10px; height: 10px;
      background: var(--accent); border-radius: 50%;
      box-shadow: 0 0 12px var(--accent);
    }
    .nav-links {
      display: flex; gap: 32px; list-style: none;
    }
    .nav-links a {
      text-decoration: none; font-size: 0.9rem;
      color: var(--ink-muted); font-weight: 500;
      transition: color .2s;
    }
    .nav-links a:hover { color: var(--accent); }
    .nav-cta {
      background: var(--accent); color: var(--white);
      border: none; border-radius: 50px;
      padding: 10px 24px; font-size: 0.9rem;
      font-family: 'DM Sans', sans-serif; font-weight: 500;
      cursor: pointer; transition: all .25s;
      text-decoration: none;
    }
    .nav-cta:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px var(--accent-glow);
    }
    .nav-mobile-btn { display: none; background: none; border: none; cursor: pointer; }

    /* ─── HERO ─────────────────────────────── */
    #hero {
      min-height: auto;
      padding: 120px 6vw 80px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      align-items: center;
      gap: 40px;
      position: relative;
      overflow: hidden;
    }
    .hero-blob {
      position: absolute; border-radius: 50%;
      filter: blur(90px); pointer-events: none;
    }
    .blob-1 {
      width: 560px; height: 560px;
      background: radial-gradient(circle, rgba(92,74,255,0.18), transparent 70%);
      top: -100px; right: -100px;
      animation: drift 8s ease-in-out infinite alternate;
    }
    .blob-2 {
      width: 400px; height: 400px;
      background: radial-gradient(circle, rgba(0,201,177,0.14), transparent 70%);
      bottom: 0px; left: -60px;
      animation: drift 11s ease-in-out infinite alternate-reverse;
    }
    @keyframes drift {
      from { transform: translate(0, 0) scale(1); }
      to   { transform: translate(40px, 30px) scale(1.08); }
    }

    .hero-left { position: relative; z-index: 1; }
    .hero-badge {
      display: inline-flex; align-items: center; gap: 8px;
      background: var(--accent-light);
      color: var(--accent); font-size: 0.78rem; font-weight: 600;
      padding: 6px 16px; border-radius: 50px;
      margin-bottom: 28px; letter-spacing: .04em;
      animation: fadeUp .6s ease both;
    }
    .hero-badge span { width: 6px; height: 6px; border-radius: 50%; background: var(--accent); }
    .hero-title {
      font-family: 'Syne', sans-serif;
      font-size: clamp(2.2rem, 5vw, 3.6rem);
      font-weight: 800; line-height: 1.2;
      margin-bottom: 22px;
      animation: fadeUp .65s .1s ease both;
    }
    .hero-title .accent { color: var(--accent); }
    .hero-title .teal { color: var(--teal); }
    .hero-desc {
      font-size: 1rem; color: var(--ink-muted);
      line-height: 1.6; max-width: 480px;
      margin-bottom: 32px;
      animation: fadeUp .7s .2s ease both;
      font-weight: 300;
    }
    .hero-actions {
      display: flex; gap: 16px; flex-wrap: wrap;
      animation: fadeUp .75s .3s ease both;
    }
    .btn-primary {
      background: var(--accent); color: var(--white);
      border: none; border-radius: 50px;
      padding: 12px 28px; font-size: 0.95rem;
      font-family: 'DM Sans', sans-serif; font-weight: 500;
      cursor: pointer; transition: all .25s;
      text-decoration: none; display: inline-block;
    }
    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 32px var(--accent-glow);
    }
    .btn-outline {
      background: transparent; color: var(--ink);
      border: 2px solid var(--ink); border-radius: 50px;
      padding: 11px 28px; font-size: 0.95rem;
      font-family: 'DM Sans', sans-serif; font-weight: 500;
      cursor: pointer; transition: all .25s;
      text-decoration: none; display: inline-block;
    }
    .btn-outline:hover {
      border-color: var(--accent); color: var(--accent);
      transform: translateY(-2px);
    }
    .hero-stats {
      margin-top: 42px;
      display: flex; gap: 32px;
      animation: fadeUp .8s .45s ease both;
    }
    .stat-item { }
    .stat-num {
      font-family: 'Syne', sans-serif; font-size: 1.6rem;
      font-weight: 700; color: var(--ink);
    }
    .stat-label { font-size: 0.75rem; color: var(--ink-faint); margin-top: 2px; }

    /* Hero right: mock dashboard (lebih ringkas & tidak over-stretch) */
    .hero-right {
      position: relative; z-index: 1;
      animation: fadeUp .8s .2s ease both;
      max-width: 100%;
    }
    .dashboard-card {
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: 0 20px 40px rgba(13,13,20,.08);
      padding: 22px;
      overflow: hidden;
    }
    .dash-header {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 20px;
    }
    .dash-title {
      font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.95rem;
    }
    .dash-tag {
      font-size: 0.68rem; background: #eefcf9;
      color: var(--teal); padding: 4px 10px; border-radius: 50px;
      font-weight: 600;
    }
    .dash-grid {
      display: grid; grid-template-columns: repeat(3, 1fr);
      gap: 12px; margin-bottom: 20px;
    }
    .dash-mini {
      background: var(--surface);
      border-radius: var(--radius-sm);
      padding: 10px 12px;
    }
    .dash-mini-label { font-size: 0.65rem; color: var(--ink-faint); margin-bottom: 4px; }
    .dash-mini-val {
      font-family: 'Syne', sans-serif; font-size: 1.2rem; font-weight: 700;
    }
    .dash-mini-val.c1 { color: var(--accent); }
    .dash-mini-val.c2 { color: var(--teal); }
    .dash-mini-val.c3 { color: var(--amber); }
    .dash-mini-trend {
      font-size: 0.62rem; margin-top: 3px;
    }
    .up { color: var(--teal); }
    .dn { color: var(--rose); }

    .dash-bar-section { margin-bottom: 16px; }
    .dash-bar-title {
      font-size: 0.7rem; color: var(--ink-muted); margin-bottom: 8px;
      font-weight: 500;
    }
    .bar-row { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
    .bar-name { font-size: 0.68rem; color: var(--ink-muted); width: 80px; flex-shrink: 0; }
    .bar-track { flex: 1; background: var(--surface); border-radius: 4px; height: 6px; overflow: hidden; }
    .bar-fill { height: 100%; border-radius: 4px; }
    .bar-pct { font-size: 0.65rem; color: var(--ink-faint); width: 30px; text-align: right; flex-shrink: 0; }

    .dash-list {}
    .dash-list-item {
      display: flex; align-items: center; justify-content: space-between;
      padding: 8px 0; border-bottom: 1px solid var(--surface);
    }
    .dash-list-item:last-child { border-bottom: none; }
    .dash-item-name { font-size: 0.74rem; font-weight: 500; }
    .dash-item-loc { font-size: 0.62rem; color: var(--ink-faint); }
    .dash-badge {
      font-size: 0.6rem; padding: 2px 8px; border-radius: 50px; font-weight: 600;
    }
    .badge-ok { background: #eefcf9; color: var(--teal); }
    .badge-warn { background: #fff7e6; color: var(--amber); }
    .badge-err { background: #fff0f3; color: var(--rose); }

    /* floating elements lebih kecil dan proporsional */
    .float-notif {
      position: absolute;
      top: -12px; left: -20px;
      background: var(--white);
      border-radius: 12px;
      padding: 8px 14px;
      box-shadow: 0 10px 20px rgba(13,13,20,.1);
      display: flex; align-items: center; gap: 8px;
      font-size: 0.7rem;
      animation: floatPop 1s .9s ease both;
      z-index: 2;
    }
    .float-notif .icon { font-size: 1rem; }
    .float-notif strong { display: block; font-size: 0.75rem; color: var(--ink); }
    .float-notif span { font-size: 0.6rem; color: var(--ink-faint); }
    .float-tag {
      position: absolute;
      bottom: -12px; right: -12px;
      background: var(--accent);
      color: var(--white);
      border-radius: 10px;
      padding: 6px 14px;
      font-family: 'Syne', sans-serif; font-size: 0.7rem; font-weight: 700;
      box-shadow: 0 6px 16px var(--accent-glow);
      animation: floatPop 1s 1.1s ease both;
      z-index: 2;
    }
    @keyframes floatPop {
      from { opacity: 0; transform: scale(.8) translateY(10px); }
      to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* ─── FEATURES ─────────────────────────────── */
    #features {
      padding: 80px 6vw;
    }
    .section-label {
      text-align: center;
      font-size: 0.78rem; letter-spacing: .1em;
      color: var(--accent); font-weight: 600;
      text-transform: uppercase; margin-bottom: 14px;
    }
    .section-title {
      text-align: center;
      font-family: 'Syne', sans-serif;
      font-size: clamp(1.8rem, 3.5vw, 2.6rem);
      font-weight: 800; margin-bottom: 16px;
    }
    .section-desc {
      text-align: center;
      color: var(--ink-muted); font-size: 0.95rem;
      max-width: 520px; margin: 0 auto 50px;
      line-height: 1.6; font-weight: 300;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
    }
    .feature-card {
      background: var(--white);
      border-radius: var(--radius);
      padding: 28px;
      border: 1px solid rgba(92,74,255,0.07);
      transition: all .3s;
      cursor: default;
    }
    .feature-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 32px rgba(92,74,255,0.08);
      border-color: rgba(92,74,255,0.2);
    }
    .feature-icon {
      width: 48px; height: 48px;
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; margin-bottom: 18px;
    }
    .fi-1 { background: var(--accent-light); }
    .fi-2 { background: #e7fdf9; }
    .fi-3 { background: #fff8e6; }
    .fi-4 { background: #fff0f5; }
    .fi-5 { background: #f0f6ff; }
    .fi-6 { background: #f0fef0; }
    .feature-title {
      font-family: 'Syne', sans-serif; font-weight: 700;
      font-size: 1rem; margin-bottom: 8px;
    }
    .feature-desc {
      font-size: 0.84rem; color: var(--ink-muted);
      line-height: 1.6; font-weight: 300;
    }

    /* ─── TESTIMONIALS ─────────────────────────────── */
    #testimonials {
      padding: 80px 6vw;
      background: var(--ink);
      color: var(--white);
      position: relative; overflow: hidden;
    }
    #testimonials .section-label { color: var(--teal); }
    #testimonials .section-title { color: var(--white); }
    #testimonials .section-desc { color: rgba(255,255,255,0.5); }
    .testi-blob {
      position: absolute; border-radius: 50%;
      filter: blur(80px); pointer-events: none;
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(92,74,255,0.2), transparent 70%);
      top: -100px; right: -100px;
    }

    .testi-grid {
      display: grid; grid-template-columns: repeat(3, 1fr);
      gap: 24px; position: relative; z-index: 1;
    }
    .testi-card {
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: var(--radius);
      padding: 28px;
      transition: all .3s;
    }
    .testi-card:hover {
      background: rgba(255,255,255,0.1);
      transform: translateY(-4px);
    }
    .testi-stars {
      color: var(--amber); font-size: 0.85rem; margin-bottom: 14px;
    }
    .testi-quote {
      font-size: 0.9rem; line-height: 1.6;
      color: rgba(255,255,255,0.8); font-weight: 300;
      margin-bottom: 20px;
      font-style: italic;
    }
    .testi-author { display: flex; align-items: center; gap: 12px; }
    .testi-avatar {
      width: 38px; height: 38px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 0.85rem;
      flex-shrink: 0;
    }
    .av1 { background: linear-gradient(135deg, var(--accent), var(--teal)); }
    .av2 { background: linear-gradient(135deg, var(--teal), var(--amber)); }
    .av3 { background: linear-gradient(135deg, var(--rose), var(--accent)); }
    .testi-name {
      font-weight: 600; font-size: 0.85rem; color: var(--white);
    }
    .testi-role {
      font-size: 0.7rem; color: rgba(255,255,255,0.4); margin-top: 2px;
    }

    /* ─── CTA ─────────────────────────────── */
    #cta {
      padding: 80px 6vw;
      text-align: center;
      position: relative; overflow: hidden;
    }
    .cta-inner {
      background: var(--accent);
      border-radius: 28px;
      padding: 60px 32px;
      position: relative; overflow: hidden;
    }
    .cta-inner::before {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(circle at 70% 30%, rgba(0,201,177,0.25), transparent 60%);
    }
    .cta-inner .section-label { color: rgba(255,255,255,0.7); }
    .cta-title {
      font-family: 'Syne', sans-serif;
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      font-weight: 800; color: var(--white);
      margin-bottom: 16px; position: relative; z-index: 1;
    }
    .cta-desc {
      color: rgba(255,255,255,0.8); font-size: 0.95rem;
      max-width: 480px; margin: 0 auto 32px;
      line-height: 1.6; font-weight: 300;
      position: relative; z-index: 1;
    }
    .cta-actions {
      display: flex; gap: 16px; justify-content: center;
      flex-wrap: wrap; position: relative; z-index: 1;
    }
    .btn-white {
      background: var(--white); color: var(--accent);
      border: none; border-radius: 50px;
      padding: 12px 28px; font-size: 0.95rem;
      font-family: 'DM Sans', sans-serif; font-weight: 600;
      cursor: pointer; transition: all .25s;
      text-decoration: none; display: inline-block;
    }
    .btn-white:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 24px rgba(0,0,0,.15);
    }
    .btn-ghost {
      background: rgba(255,255,255,0.15);
      color: var(--white); border: 1.5px solid rgba(255,255,255,0.4);
      border-radius: 50px; padding: 11px 26px;
      font-size: 0.95rem; font-family: 'DM Sans', sans-serif;
      font-weight: 500; cursor: pointer; transition: all .25s;
      text-decoration: none; display: inline-block;
    }
    .btn-ghost:hover {
      background: rgba(255,255,255,0.25);
      transform: translateY(-2px);
    }
    .cta-deco {
      position: absolute; font-family: 'Syne', sans-serif;
      font-weight: 800; font-size: 8rem;
      color: rgba(255,255,255,0.06); pointer-events: none;
      line-height: 1;
    }
    .cta-deco.d1 { top: -20px; left: -20px; }
    .cta-deco.d2 { bottom: -30px; right: -10px; }

    /* ─── FOOTER ─────────────────────────────── */
    footer {
      background: var(--ink); color: var(--white);
      padding: 64px 6vw 32px;
    }
    .footer-top {
      display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 40px; margin-bottom: 48px;
    }
    .footer-brand-logo {
      font-family: 'Syne', sans-serif;
      font-weight: 800; font-size: 1.3rem;
      display: flex; align-items: center; gap: 10px;
      margin-bottom: 14px;
    }
    .footer-brand-logo .dot { width: 10px; height: 10px; background: var(--accent); border-radius: 50%; box-shadow: 0 0 12px var(--accent); }
    .footer-tagline {
      font-size: 0.85rem; color: rgba(255,255,255,0.45);
      line-height: 1.6; font-weight: 300; max-width: 280px;
    }
    .footer-social { display: flex; gap: 10px; margin-top: 20px; }
    .social-btn {
      width: 34px; height: 34px; background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 10px; display: flex; align-items: center; justify-content: center;
      text-decoration: none; font-size: 0.8rem;
      transition: all .2s;
    }
    .social-btn:hover { background: var(--accent); border-color: var(--accent); }

    .footer-col h4 {
      font-family: 'Syne', sans-serif; font-weight: 700;
      font-size: 0.85rem; color: var(--white);
      margin-bottom: 16px; letter-spacing: .04em;
    }
    .footer-links { list-style: none; }
    .footer-links li { margin-bottom: 8px; }
    .footer-links a {
      text-decoration: none; font-size: 0.8rem;
      color: rgba(255,255,255,0.45); transition: color .2s;
    }
    .footer-links a:hover { color: var(--white); }

    .footer-bottom {
      border-top: 1px solid rgba(255,255,255,0.1);
      padding-top: 24px;
      display: flex; align-items: center; justify-content: space-between;
    }
    .footer-copy {
      font-size: 0.75rem; color: rgba(255,255,255,0.3);
    }
    .footer-bottom-links { display: flex; gap: 24px; }
    .footer-bottom-links a {
      font-size: 0.75rem; color: rgba(255,255,255,0.3);
      text-decoration: none; transition: color .2s;
    }
    .footer-bottom-links a:hover { color: var(--white); }

    /* ─── ANIMATIONS ─────────────────────────────── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .reveal {
      opacity: 0; transform: translateY(24px);
      transition: opacity .6s ease, transform .6s ease;
    }
    .reveal.visible {
      opacity: 1; transform: translateY(0);
    }

    /* ─── RESPONSIVE ─────────────────────────────── */
    @media (max-width: 1024px) {
      .features-grid { grid-template-columns: repeat(2, 1fr); }
      .testi-grid { grid-template-columns: repeat(2, 1fr); }
      .footer-top { grid-template-columns: 1fr 1fr; gap: 32px; }
    }

    @media (max-width: 900px) {
      #hero { grid-template-columns: 1fr; padding: 110px 5vw 60px; gap: 40px; }
      .hero-right { max-width: 550px; margin: 0 auto; }
      .hero-stats { gap: 24px; justify-content: center; }
      .hero-left { text-align: center; }
      .hero-desc { margin-left: auto; margin-right: auto; }
      .hero-actions { justify-content: center; }
      .hero-stats { justify-content: center; }
    }

    @media (max-width: 768px) {
      .features-grid { grid-template-columns: 1fr; }
      .testi-grid { grid-template-columns: 1fr; }
      .nav-links { display: none; }
      .nav-mobile-btn { display: flex; flex-direction: column; gap: 5px; }
      .nav-mobile-btn span { width: 24px; height: 2px; background: var(--ink); border-radius: 2px; }
      .footer-top { grid-template-columns: 1fr; gap: 28px; }
      .footer-bottom { flex-direction: column; gap: 16px; text-align: center; }
      .footer-bottom-links { justify-content: center; }
      .cta-inner { padding: 48px 24px; }
      .hero-stats .stat-item { text-align: center; }
    }
  </style>
</head>
<body>

  <!-- NAV -->
  <nav>
    <a href="#" class="nav-logo">
      <span class="dot"></span>AsetKu
    </a>
    <ul class="nav-links">
      <li><a href="#features">Fitur</a></li>
      <li><a href="#testimonials">Testimoni</a></li>
      <li><a href="#cta">Harga</a></li>
    </ul>
    <a href="#cta" class="nav-cta">Mulai Gratis</a>
    <button class="nav-mobile-btn" aria-label="menu">
      <span></span><span></span><span></span>
    </button>
  </nav>

  <!-- HERO -->
  <section id="hero">
    <div class="hero-blob blob-1"></div>
    <div class="hero-blob blob-2"></div>

    <div class="hero-left">
      <div class="hero-badge"><span></span> Platform Aset No. 1 di Indonesia</div>
      <h1 class="hero-title">
        Kelola Semua<br>Aset Instansi<br>
        <span class="accent">Lebih Cerdas,</span><br>
        <span class="teal">Lebih Efisien.</span>
      </h1>
      <p class="hero-desc">
        AsetKu membantu Anda memantau, mencatat, dan mengelola seluruh
        inventaris aset dalam satu dasbor yang intuitif dan terintegrasi penuh.
      </p>
      <div class="hero-actions">
        <a href="#cta" class="btn-primary">Coba Gratis 14 Hari →</a>
        <a href="#features" class="btn-outline">Lihat Fitur</a>
      </div>
      <div class="hero-stats">
        <div class="stat-item">
          <div class="stat-num">2.800+</div>
          <div class="stat-label">Pengguna Aktif</div>
        </div>
        <div class="stat-item">
          <div class="stat-num">98%</div>
          <div class="stat-label">Kepuasan Pengguna</div>
        </div>
        <div class="stat-item">
          <div class="stat-num">500K+</div>
          <div class="stat-label">Aset Terpantau</div>
        </div>
      </div>
    </div>

    <div class="hero-right">
      <div style="position:relative; padding: 20px 20px 30px 20px;">
        <div class="float-notif">
          <span class="icon">✅</span>
          <div>
            <strong>Aset Diverifikasi</strong>
            <span>Laptop Dell XPS — baru saja</span>
          </div>
        </div>
        <div class="dashboard-card">
          <div class="dash-header">
            <span class="dash-title">Ringkasan Aset</span>
            <span class="dash-tag">Live Update</span>
          </div>
          <div class="dash-grid">
            <div class="dash-mini">
              <div class="dash-mini-label">Total Aset</div>
              <div class="dash-mini-val c1">1.248</div>
              <div class="dash-mini-trend up">↑ 3.2% bulan ini</div>
            </div>
            <div class="dash-mini">
              <div class="dash-mini-label">Kondisi Baik</div>
              <div class="dash-mini-val c2">1.101</div>
              <div class="dash-mini-trend up">↑ 88.2%</div>
            </div>
            <div class="dash-mini">
              <div class="dash-mini-label">Perlu Servis</div>
              <div class="dash-mini-val c3">147</div>
              <div class="dash-mini-trend dn">↓ 11.8%</div>
            </div>
          </div>
          <div class="dash-bar-section">
            <div class="dash-bar-title">Distribusi Kategori</div>
            <div class="bar-row">
              <span class="bar-name">Elektronik</span>
              <div class="bar-track">
                <div class="bar-fill" style="width:72%;background:var(--accent)"></div>
              </div>
              <span class="bar-pct">72%</span>
            </div>
            <div class="bar-row">
              <span class="bar-name">Furnitur</span>
              <div class="bar-track">
                <div class="bar-fill" style="width:55%;background:var(--teal)"></div>
              </div>
              <span class="bar-pct">55%</span>
            </div>
            <div class="bar-row">
              <span class="bar-name">Kendaraan</span>
              <div class="bar-track">
                <div class="bar-fill" style="width:33%;background:var(--amber)"></div>
              </div>
              <span class="bar-pct">33%</span>
            </div>
          </div>
          <div class="dash-list">
            <div class="dash-list-item">
              <div>
                <div class="dash-item-name">MacBook Pro M3</div>
                <div class="dash-item-loc">IT Dept — Lantai 3</div>
              </div>
              <span class="dash-badge badge-ok">Aktif</span>
            </div>
            <div class="dash-list-item">
              <div>
                <div class="dash-item-name">Printer Canon MX498</div>
                <div class="dash-item-loc">Admin — Lantai 1</div>
              </div>
              <span class="dash-badge badge-warn">Servis</span>
            </div>
            <div class="dash-list-item">
              <div>
                <div class="dash-item-name">Proyektor Epson EB</div>
                <div class="dash-item-loc">Aula — Gedung B</div>
              </div>
              <span class="dash-badge badge-ok">Aktif</span>
            </div>
          </div>
        </div>
        <div class="float-tag">📈 Efisiensi +34%</div>
      </div>
    </div>
  </section>

  <!-- FEATURES -->
  <section id="features">
    <p class="section-label reveal">Kenapa AsetKu?</p>
    <h2 class="section-title reveal">Semua yang Anda Butuhkan<br>Ada di Sini</h2>
    <p class="section-desc reveal">
      Dari pencatatan hingga pelaporan, kami menyederhanakan pengelolaan aset
      agar tim Anda bisa fokus pada hal yang lebih penting.
    </p>
    <div class="features-grid">
      <div class="feature-card reveal"><div class="feature-icon fi-1">📊</div><h3 class="feature-title">Dasbor Real-Time</h3><p class="feature-desc">Pantau kondisi seluruh aset secara langsung dengan visualisasi data yang mudah dibaca dan selalu diperbarui.</p></div>
      <div class="feature-card reveal"><div class="feature-icon fi-2">🔍</div><h3 class="feature-title">Lacak & Verifikasi</h3><p class="feature-desc">Temukan aset manapun dalam hitungan detik menggunakan fitur pencarian canggih berdasarkan lokasi, kategori, dan status.</p></div>
      <div class="feature-card reveal"><div class="feature-icon fi-3">🔔</div><h3 class="feature-title">Notifikasi Otomatis</h3><p class="feature-desc">Terima peringatan otomatis saat jadwal perawatan tiba, masa garansi habis, atau ada aset yang membutuhkan perhatian.</p></div>
      <div class="feature-card reveal"><div class="feature-icon fi-4">📄</div><h3 class="feature-title">Laporan & Ekspor</h3><p class="feature-desc">Buat laporan inventaris profesional dalam berbagai format (PDF, Excel) hanya dengan satu klik, siap untuk audit kapan saja.</p></div>
      <div class="feature-card reveal"><div class="feature-icon fi-5">👥</div><h3 class="feature-title">Manajemen Tim</h3><p class="feature-desc">Tetapkan hak akses berbeda untuk setiap anggota tim — dari admin pusat hingga petugas lapangan, semua terkendali.</p></div>
      <div class="feature-card reveal"><div class="feature-icon fi-6">🔒</div><h3 class="feature-title">Keamanan Berlapis</h3><p class="feature-desc">Data Anda dilindungi dengan enkripsi end-to-end, pencadangan otomatis harian, dan autentikasi dua faktor.</p></div>
    </div>
  </section>

  <!-- TESTIMONIALS -->
  <section id="testimonials">
    <div class="testi-blob"></div>
    <p class="section-label reveal">Testimoni</p>
    <h2 class="section-title reveal">Dipercaya Ribuan Instansi</h2>
    <p class="section-desc reveal">Dengarkan langsung dari mereka yang sudah merasakan manfaatnya.</p>
    <div class="testi-grid">
      <div class="testi-card reveal"><div class="testi-stars">★★★★★</div><p class="testi-quote">"Sebelum pakai AsetKu, laporan inventaris kami selalu terlambat dan penuh kesalahan. Sekarang proses audit tahunan selesai dalam 2 hari — luar biasa efisien."</p><div class="testi-author"><div class="testi-avatar av1">BW</div><div><div class="testi-name">Budi Wicaksono</div><div class="testi-role">Kepala Bagian Umum, Dinas Pendidikan Kota</div></div></div></div>
      <div class="testi-card reveal"><div class="testi-stars">★★★★★</div><p class="testi-quote">"Fitur notifikasi perawatan benar-benar menyelamatkan anggaran kami. Tidak ada lagi kerusakan mendadak yang mahal karena perawatan selalu tepat waktu."</p><div class="testi-author"><div class="testi-avatar av2">SR</div><div><div class="testi-name">Siti Rahayu</div><div class="testi-role">Manajer Fasilitas, RS Medika Utama</div></div></div></div>
      <div class="testi-card reveal"><div class="testi-stars">★★★★★</div><p class="testi-quote">"Onboarding timnya super cepat. Dalam satu minggu, lebih dari 300 aset kami sudah tercatat semua. Tim support juga sangat responsif dan membantu."</p><div class="testi-author"><div class="testi-avatar av3">DP</div><div><div class="testi-name">Dian Pratama</div><div class="testi-role">CTO, Startup Teknologi Nusantara</div></div></div></div>
    </div>
  </section>

  <!-- CTA -->
  <section id="cta">
    <div class="cta-inner">
      <span class="cta-deco d1">★</span>
      <span class="cta-deco d2">◆</span>
      <p class="section-label reveal">Mulai Sekarang</p>
      <h2 class="cta-title reveal">Transformasi Pengelolaan<br>Aset Instansi Anda</h2>
      <p class="cta-desc reveal">Bergabunglah dengan ribuan instansi yang sudah mempercayakan manajemen aset mereka kepada AsetKu. Gratis 14 hari, tanpa kartu kredit.</p>
      <div class="cta-actions reveal"><a href="#" class="btn-white">Daftar Gratis Sekarang</a><a href="#" class="btn-ghost">Jadwalkan Demo</a></div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-top"><div><div class="footer-brand-logo"><span class="dot"></span>AsetKu</div><p class="footer-tagline">Platform manajemen aset terpadu untuk instansi pemerintah dan swasta di seluruh Indonesia. Sederhana, andal, dan aman.</p><div class="footer-social"><a href="#" class="social-btn">𝕏</a><a href="#" class="social-btn">in</a><a href="#" class="social-btn">ig</a><a href="#" class="social-btn">yt</a></div></div><div class="footer-col"><h4>Produk</h4><ul class="footer-links"><li><a href="#">Fitur</a></li><li><a href="#">Harga</a></li><li><a href="#">Keamanan</a></li><li><a href="#">Changelog</a></li><li><a href="#">Roadmap</a></li></ul></div><div class="footer-col"><h4>Perusahaan</h4><ul class="footer-links"><li><a href="#">Tentang Kami</a></li><li><a href="#">Blog</a></li><li><a href="#">Karir</a></li><li><a href="#">Pers</a></li><li><a href="#">Partner</a></li></ul></div><div class="footer-col"><h4>Dukungan</h4><ul class="footer-links"><li><a href="#">Dokumentasi</a></li><li><a href="#">Pusat Bantuan</a></li><li><a href="#">Kontak</a></li><li><a href="#">Status Sistem</a></li><li><a href="#">Komunitas</a></li></ul></div></div>
    <div class="footer-bottom"><span class="footer-copy">© 2025 AsetKu. Hak cipta dilindungi.</span><div class="footer-bottom-links"><a href="#">Kebijakan Privasi</a><a href="#">Syarat & Ketentuan</a><a href="#">Cookie</a></div></div>
  </footer>

  <script>
    const reveals = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver((entries) => {
      entries.forEach((e, i) => {
        if (e.isIntersecting) {
          e.target.style.transitionDelay = (i % 3) * 0.08 + 's';
          e.target.classList.add('visible');
        }
      });
    }, { threshold: 0.12 });
    reveals.forEach(el => io.observe(el));

    const nav = document.querySelector('nav');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 40) nav.style.boxShadow = '0 4px 20px rgba(13,13,20,0.08)';
      else nav.style.boxShadow = 'none';
    });
  </script>
</body>
</html>