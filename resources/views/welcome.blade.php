<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AsetKu — Manajemen Aset Instansi</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg: #f9f9f7;
      --white: #ffffff;
      --ink: #111111;
      --muted: #6b6b6b;
      --faint: #b0b0b0;
      --border: #e8e8e4;
      --accent: #2563eb;
      --accent-light: #eff4ff;
      --green: #16a34a;
      --green-light: #f0fdf4;
      --amber: #d97706;
      --amber-light: #fffbeb;
      --radius: 16px;
      --max: 1100px;
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--bg);
      color: var(--ink);
      line-height: 1.6;
      overflow-x: hidden;
    }

    /* NAV */
    nav {
      position: sticky; top: 0; z-index: 100;
      background: rgba(249,249,247,0.92);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border);
      padding: 0 24px;
    }
    .nav-inner {
      max-width: var(--max); margin: 0 auto;
      height: 60px;
      display: flex; align-items: center; justify-content: space-between;
    }
    .nav-logo {
      font-weight: 800; font-size: 1.1rem;
      color: var(--ink); text-decoration: none;
      letter-spacing: -0.02em;
      display: flex; align-items: center; gap: 8px;
    }
    .logo-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); }
    .nav-links { display: flex; gap: 28px; list-style: none; }
    .nav-links a {
      text-decoration: none; font-size: 0.875rem;
      color: var(--muted); font-weight: 500; transition: color .15s;
    }
    .nav-links a:hover { color: var(--ink); }
    .nav-btn {
      background: var(--ink); color: var(--white);
      border: none; border-radius: 8px;
      padding: 9px 20px; font-size: 0.875rem;
      font-family: inherit; font-weight: 600;
      cursor: pointer; transition: opacity .15s;
      text-decoration: none; display: inline-block;
    }
    .nav-btn:hover { opacity: 0.82; }

    /* HERO */
    #hero {
      max-width: var(--max); margin: 0 auto;
      padding: 80px 24px 64px;
      text-align: center;
    }
    .hero-tag {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--accent-light); color: var(--accent);
      font-size: 0.75rem; font-weight: 600;
      padding: 5px 14px; border-radius: 100px;
      margin-bottom: 24px; border: 1px solid #c7d9ff;
      animation: fadeUp 0.5s ease both;
    }
    .hero-tag-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--accent); }
    h1 {
      font-size: clamp(2rem, 5vw, 3.25rem);
      font-weight: 800; line-height: 1.15;
      letter-spacing: -0.03em; margin-bottom: 20px;
      animation: fadeUp 0.5s 0.05s ease both;
    }
    h1 em { color: var(--accent); font-style: normal; }
    .hero-sub {
      font-size: 1rem; color: var(--muted);
      max-width: 500px; margin: 0 auto 36px;
      animation: fadeUp 0.5s 0.1s ease both;
    }
    .hero-actions {
      display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;
      animation: fadeUp 0.5s 0.15s ease both; margin-bottom: 48px;
    }
    .btn-primary {
      background: var(--accent); color: var(--white);
      border: none; border-radius: 10px;
      padding: 12px 28px; font-size: 0.9rem;
      font-family: inherit; font-weight: 600;
      cursor: pointer; transition: background .2s;
      text-decoration: none;
    }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-secondary {
      background: var(--white); color: var(--ink);
      border: 1.5px solid var(--border); border-radius: 10px;
      padding: 11px 28px; font-size: 0.9rem;
      font-family: inherit; font-weight: 500;
      cursor: pointer; transition: border-color .2s;
      text-decoration: none;
    }
    .btn-secondary:hover { border-color: #bbb; }

    /* Stats row */
    .hero-stats {
      display: flex; justify-content: center; gap: 0;
      background: var(--white); border: 1px solid var(--border);
      border-radius: 14px; overflow: hidden;
      max-width: 480px; margin: 0 auto 48px;
      animation: fadeUp 0.5s 0.2s ease both;
    }
    .stat { flex: 1; padding: 20px 12px; text-align: center; border-right: 1px solid var(--border); }
    .stat:last-child { border-right: none; }
    .stat-n { font-size: 1.4rem; font-weight: 800; letter-spacing: -0.03em; }
    .stat-l { font-size: 0.72rem; color: var(--muted); margin-top: 2px; }

    /* Dashboard Preview */
    .dashboard-preview {
      background: var(--white); border: 1px solid var(--border);
      border-radius: 20px; overflow: hidden;
      box-shadow: 0 4px 32px rgba(0,0,0,0.07);
      max-width: 800px; margin: 0 auto;
      animation: fadeUp 0.5s 0.25s ease both;
    }
    .preview-header {
      background: #1a1a1a; padding: 12px 18px;
      display: flex; align-items: center; gap: 7px;
    }
    .wdot { width: 11px; height: 11px; border-radius: 50%; }
    .wr { background: #ef4444; }
    .wy { background: #f59e0b; }
    .wg { background: #22c55e; }
    .preview-url {
      flex: 1; margin-left: 8px; background: rgba(255,255,255,0.08);
      border-radius: 6px; padding: 4px 12px;
      font-size: 0.72rem; color: rgba(255,255,255,0.45); font-family: monospace;
    }
    .preview-body {
      padding: 20px;
      display: grid; grid-template-columns: repeat(3, 1fr);
      gap: 12px;
    }
    .pcard {
      background: var(--bg); border: 1px solid var(--border);
      border-radius: 12px; padding: 16px;
    }
    .pcard-label {
      font-size: 0.67rem; color: var(--muted); font-weight: 600;
      text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 6px;
    }
    .pcard-val { font-size: 1.65rem; font-weight: 800; letter-spacing: -0.03em; }
    .pcard-val.blue { color: var(--accent); }
    .pcard-val.green { color: var(--green); }
    .pcard-val.amber { color: var(--amber); }
    .pcard-sub { font-size: 0.68rem; margin-top: 4px; }
    .up { color: var(--green); }
    .dn { color: var(--amber); }

    .plist {
      grid-column: 1 / -1; background: var(--bg);
      border: 1px solid var(--border); border-radius: 12px; overflow: hidden;
    }
    .plist-head {
      padding: 10px 16px; border-bottom: 1px solid var(--border);
      display: grid; grid-template-columns: 1fr auto auto;
      gap: 12px;
      font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
      letter-spacing: 0.07em; color: var(--muted);
    }
    .plist-row {
      padding: 11px 16px; border-bottom: 1px solid var(--border);
      display: grid; grid-template-columns: 1fr auto auto;
      gap: 12px; align-items: center;
    }
    .plist-row:last-child { border-bottom: none; }
    .pname { font-size: 0.8rem; font-weight: 600; }
    .ploc { font-size: 0.67rem; color: var(--muted); margin-top: 1px; }
    .pdept { font-size: 0.72rem; color: var(--muted); }
    .badge {
      font-size: 0.64rem; font-weight: 700;
      padding: 3px 10px; border-radius: 100px;
    }
    .badge-ok { background: var(--green-light); color: var(--green); }
    .badge-warn { background: var(--amber-light); color: var(--amber); }

    /* FEATURES */
    #features { padding: 80px 24px; max-width: var(--max); margin: 0 auto; }
    .section-label {
      font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
      letter-spacing: 0.1em; color: var(--accent); margin-bottom: 10px;
    }
    .section-title {
      font-size: clamp(1.6rem, 3vw, 2.25rem);
      font-weight: 800; letter-spacing: -0.03em;
      margin-bottom: 10px; line-height: 1.2;
    }
    .section-sub {
      font-size: 0.9rem; color: var(--muted); max-width: 420px;
    }
    .features-grid {
      display: grid; grid-template-columns: repeat(3, 1fr);
      gap: 14px; margin-top: 40px;
    }
    .feat-card {
      background: var(--white); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 24px;
      transition: box-shadow .2s;
    }
    .feat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.06); }
    .feat-icon {
      width: 40px; height: 40px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.15rem; margin-bottom: 14px;
    }
    .feat-title { font-size: 0.9rem; font-weight: 700; margin-bottom: 6px; }
    .feat-desc { font-size: 0.82rem; color: var(--muted); line-height: 1.65; }

    /* TESTIMONIALS */
    #testimonials { padding: 80px 24px; background: var(--ink); }
    .testi-inner { max-width: var(--max); margin: 0 auto; }
    #testimonials .section-label { color: #60a5fa; }
    #testimonials .section-title { color: var(--white); }
    #testimonials .section-sub { color: rgba(255,255,255,0.45); }
    .testi-grid {
      display: grid; grid-template-columns: repeat(3, 1fr);
      gap: 14px; margin-top: 40px;
    }
    .testi-card {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: var(--radius); padding: 24px;
      transition: background .2s;
    }
    .testi-card:hover { background: rgba(255,255,255,0.08); }
    .testi-stars { color: #f59e0b; font-size: 0.78rem; margin-bottom: 14px; letter-spacing: 1px; }
    .testi-text {
      font-size: 0.875rem; color: rgba(255,255,255,0.72);
      line-height: 1.7; margin-bottom: 20px; font-style: italic;
    }
    .testi-author { display: flex; align-items: center; gap: 10px; }
    .testi-av {
      width: 34px; height: 34px; border-radius: 50%;
      background: rgba(255,255,255,0.12);
      display: flex; align-items: center; justify-content: center;
      font-size: 0.72rem; font-weight: 700; color: white; flex-shrink: 0;
    }
    .testi-name { font-size: 0.82rem; font-weight: 700; color: white; }
    .testi-role { font-size: 0.68rem; color: rgba(255,255,255,0.38); margin-top: 2px; }

    /* CTA */
    #cta { padding: 80px 24px; max-width: var(--max); margin: 0 auto; }
    .cta-box {
      background: var(--accent); border-radius: 20px;
      padding: 56px 40px; text-align: center;
      position: relative; overflow: hidden;
    }
    .cta-box::before {
      content: '';
      position: absolute; inset: 0;
      background: radial-gradient(ellipse at 80% 20%, rgba(255,255,255,0.12), transparent 60%);
    }
    .cta-title {
      font-size: clamp(1.5rem, 3vw, 2.1rem);
      font-weight: 800; color: white; letter-spacing: -0.03em;
      margin-bottom: 12px; position: relative;
    }
    .cta-sub {
      font-size: 0.95rem; color: rgba(255,255,255,0.8);
      max-width: 420px; margin: 0 auto 32px; position: relative;
    }
    .cta-acts { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; position: relative; }
    .btn-white {
      background: white; color: var(--accent);
      border: none; border-radius: 10px;
      padding: 12px 28px; font-size: 0.9rem;
      font-family: inherit; font-weight: 700;
      cursor: pointer; transition: opacity .15s; text-decoration: none;
    }
    .btn-white:hover { opacity: 0.9; }
    .btn-ghost {
      background: transparent; color: white;
      border: 1.5px solid rgba(255,255,255,0.4); border-radius: 10px;
      padding: 11px 26px; font-size: 0.9rem;
      font-family: inherit; font-weight: 500;
      cursor: pointer; transition: background .15s; text-decoration: none;
    }
    .btn-ghost:hover { background: rgba(255,255,255,0.1); }

    /* FOOTER */
    footer { background: #0a0a0a; padding: 48px 24px 32px; }
    .footer-inner { max-width: var(--max); margin: 0 auto; }
    .footer-top {
      display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 40px; margin-bottom: 40px;
    }
    .footer-logo {
      font-weight: 800; font-size: 1rem; color: white;
      display: flex; align-items: center; gap: 7px; margin-bottom: 12px;
    }
    .footer-tagline { font-size: 0.8rem; color: rgba(255,255,255,0.38); line-height: 1.6; max-width: 240px; }
    .footer-col h5 {
      font-size: 0.78rem; font-weight: 700; color: white;
      margin-bottom: 14px; letter-spacing: 0.03em;
    }
    .footer-col ul { list-style: none; }
    .footer-col ul li { margin-bottom: 8px; }
    .footer-col ul a {
      font-size: 0.78rem; color: rgba(255,255,255,0.38);
      text-decoration: none; transition: color .15s;
    }
    .footer-col ul a:hover { color: white; }
    .footer-bottom {
      border-top: 1px solid rgba(255,255,255,0.08);
      padding-top: 24px;
      display: flex; align-items: center; justify-content: space-between;
    }
    .footer-copy { font-size: 0.74rem; color: rgba(255,255,255,0.28); }
    .footer-bl { display: flex; gap: 20px; }
    .footer-bl a { font-size: 0.74rem; color: rgba(255,255,255,0.28); text-decoration: none; transition: color .15s; }
    .footer-bl a:hover { color: white; }

    /* ANIMATIONS */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(14px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .reveal { opacity: 0; transform: translateY(18px); transition: opacity .5s ease, transform .5s ease; }
    .reveal.on { opacity: 1; transform: none; }

    /* RESPONSIVE */
    @media (max-width: 900px) {
      .features-grid { grid-template-columns: 1fr 1fr; }
      .testi-grid { grid-template-columns: 1fr; }
      .footer-top { grid-template-columns: 1fr 1fr; }
      .preview-body { grid-template-columns: 1fr 1fr; }
      .plist { grid-column: auto; }
    }
    @media (max-width: 640px) {
      .nav-links { display: none; }
      .features-grid { grid-template-columns: 1fr; }
      .hero-stats { max-width: 100%; }
      .footer-top { grid-template-columns: 1fr; gap: 24px; }
      .footer-bottom { flex-direction: column; gap: 10px; text-align: center; }
      .cta-box { padding: 40px 22px; }
      .preview-body { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <!-- NAV -->
  <nav>
    <div class="nav-inner">
      <a href="#" class="nav-logo">
        <span class="logo-dot"></span>AsetKu
      </a>
      <ul class="nav-links">
        <li><a href="#features">Fitur</a></li>
        <li><a href="#testimonials">Testimoni</a></li>
        <li><a href="#cta">Harga</a></li>
      </ul>
      <a href="#cta" class="nav-btn">Mulai Gratis</a>
    </div>
  </nav>

  <!-- HERO -->
  <section id="hero">
    <div class="hero-tag">
      <span class="hero-tag-dot"></span>
      Platform Aset No. 1 di Indonesia
    </div>
    <h1>Kelola Aset Instansi,<br><em>Lebih Cerdas & Efisien</em></h1>
    <p class="hero-sub">
      Pantau, catat, dan kelola seluruh inventaris aset dalam satu dasbor yang intuitif dan terintegrasi penuh.
    </p>
    <div class="hero-actions">
      <a href="#cta" class="btn-primary">Coba Gratis 14 Hari →</a>
      <a href="#features" class="btn-secondary">Lihat Fitur</a>
    </div>

    <div class="hero-stats">
      <div class="stat">
        <div class="stat-n">2.800+</div>
        <div class="stat-l">Pengguna Aktif</div>
      </div>
      <div class="stat">
        <div class="stat-n">98%</div>
        <div class="stat-l">Kepuasan Pengguna</div>
      </div>
      <div class="stat">
        <div class="stat-n">500K+</div>
        <div class="stat-l">Aset Terpantau</div>
      </div>
    </div>

    <div class="dashboard-preview">
      <div class="preview-header">
        <span class="wdot wr"></span>
        <span class="wdot wy"></span>
        <span class="wdot wg"></span>
        <span class="preview-url">asetku.id/dashboard</span>
      </div>
      <div class="preview-body">
        <div class="pcard">
          <div class="pcard-label">Total Aset</div>
          <div class="pcard-val blue">1.248</div>
          <div class="pcard-sub up">↑ 3.2% bulan ini</div>
        </div>
        <div class="pcard">
          <div class="pcard-label">Kondisi Baik</div>
          <div class="pcard-val green">1.101</div>
          <div class="pcard-sub up">↑ 88.2% dari total</div>
        </div>
        <div class="pcard">
          <div class="pcard-label">Perlu Servis</div>
          <div class="pcard-val amber">147</div>
          <div class="pcard-sub dn">↓ perlu tindakan</div>
        </div>
        <div class="plist">
          <div class="plist-head">
            <span>Nama Aset</span>
            <span>Lokasi</span>
            <span>Status</span>
          </div>
          <div class="plist-row">
            <div>
              <div class="pname">MacBook Pro M3</div>
              <div class="ploc">Departemen IT</div>
            </div>
            <div class="pdept">Lantai 3</div>
            <span class="badge badge-ok">Aktif</span>
          </div>
          <div class="plist-row">
            <div>
              <div class="pname">Printer Canon MX498</div>
              <div class="ploc">Administrasi</div>
            </div>
            <div class="pdept">Lantai 1</div>
            <span class="badge badge-warn">Servis</span>
          </div>
          <div class="plist-row">
            <div>
              <div class="pname">Proyektor Epson EB</div>
              <div class="ploc">Aula Utama</div>
            </div>
            <div class="pdept">Gedung B</div>
            <span class="badge badge-ok">Aktif</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FEATURES -->
  <section id="features">
    <p class="section-label reveal">Kenapa AsetKu?</p>
    <h2 class="section-title reveal">Semua yang Anda Butuhkan</h2>
    <p class="section-sub reveal">Dari pencatatan hingga pelaporan — kami sederhanakan pengelolaan aset agar tim bisa fokus pada hal yang lebih penting.</p>
    <div class="features-grid">
      <div class="feat-card reveal">
        <div class="feat-icon" style="background:#eff4ff">📊</div>
        <div class="feat-title">Dasbor Real-Time</div>
        <p class="feat-desc">Pantau kondisi seluruh aset secara langsung dengan visualisasi data yang selalu diperbarui.</p>
      </div>
      <div class="feat-card reveal">
        <div class="feat-icon" style="background:#f0fdf4">🔍</div>
        <div class="feat-title">Lacak & Verifikasi</div>
        <p class="feat-desc">Temukan aset manapun dalam hitungan detik berdasarkan lokasi, kategori, dan status kondisi.</p>
      </div>
      <div class="feat-card reveal">
        <div class="feat-icon" style="background:#fffbeb">🔔</div>
        <div class="feat-title">Notifikasi Otomatis</div>
        <p class="feat-desc">Terima peringatan saat jadwal perawatan tiba atau ada aset yang membutuhkan perhatian segera.</p>
      </div>
      <div class="feat-card reveal">
        <div class="feat-icon" style="background:#fff1f2">📄</div>
        <div class="feat-title">Laporan & Ekspor</div>
        <p class="feat-desc">Buat laporan inventaris dalam PDF atau Excel hanya dengan satu klik, siap untuk audit kapan saja.</p>
      </div>
      <div class="feat-card reveal">
        <div class="feat-icon" style="background:#f0f6ff">👥</div>
        <div class="feat-title">Manajemen Tim</div>
        <p class="feat-desc">Tetapkan hak akses dari admin pusat hingga petugas lapangan — semua terkendali dengan rapi.</p>
      </div>
      <div class="feat-card reveal">
        <div class="feat-icon" style="background:#f0fdf4">🔒</div>
        <div class="feat-title">Keamanan Berlapis</div>
        <p class="feat-desc">Enkripsi end-to-end, pencadangan otomatis harian, dan autentikasi dua faktor untuk keamanan penuh.</p>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS -->
  <section id="testimonials">
    <div class="testi-inner">
      <p class="section-label reveal">Testimoni</p>
      <h2 class="section-title reveal">Dipercaya Ribuan Instansi</h2>
      <p class="section-sub reveal">Dengarkan langsung dari mereka yang sudah merasakan manfaatnya.</p>
      <div class="testi-grid">
        <div class="testi-card reveal">
          <div class="testi-stars">★★★★★</div>
          <p class="testi-text">"Proses audit tahunan yang biasanya seminggu penuh, sekarang selesai dalam 2 hari. AsetKu benar-benar mengubah cara kami bekerja."</p>
          <div class="testi-author">
            <div class="testi-av">BW</div>
            <div>
              <div class="testi-name">Budi Wicaksono</div>
              <div class="testi-role">Kepala Bagian Umum, Dinas Pendidikan</div>
            </div>
          </div>
        </div>
        <div class="testi-card reveal">
          <div class="testi-stars">★★★★★</div>
          <p class="testi-text">"Fitur notifikasi perawatan menyelamatkan anggaran kami. Tidak ada lagi kerusakan mendadak yang mahal karena perawatan selalu tepat waktu."</p>
          <div class="testi-author">
            <div class="testi-av">SR</div>
            <div>
              <div class="testi-name">Siti Rahayu</div>
              <div class="testi-role">Manajer Fasilitas, RS Medika Utama</div>
            </div>
          </div>
        </div>
        <div class="testi-card reveal">
          <div class="testi-stars">★★★★★</div>
          <p class="testi-text">"Dalam satu minggu, lebih dari 300 aset kami sudah tercatat semua. Onboarding cepat dan tim support sangat responsif membantu."</p>
          <div class="testi-author">
            <div class="testi-av">DP</div>
            <div>
              <div class="testi-name">Dian Pratama</div>
              <div class="testi-role">CTO, Startup Teknologi Nusantara</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section id="cta">
    <div class="cta-box">
      <h2 class="cta-title">Mulai Kelola Aset Lebih Cerdas</h2>
      <p class="cta-sub">Bergabunglah dengan ribuan instansi yang sudah mempercayakan manajemen aset mereka kepada AsetKu. Gratis 14 hari, tanpa kartu kredit.</p>
      <div class="cta-acts">
        <a href="#" class="btn-white">Daftar Gratis Sekarang</a>
        <a href="#" class="btn-ghost">Jadwalkan Demo</a>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-inner">
      <div class="footer-top">
        <div>
          <div class="footer-logo">
            <span class="logo-dot"></span>AsetKu
          </div>
          <p class="footer-tagline">Platform manajemen aset terpadu untuk instansi pemerintah dan swasta di seluruh Indonesia.</p>
        </div>
        <div class="footer-col">
          <h5>Produk</h5>
          <ul>
            <li><a href="#">Fitur</a></li>
            <li><a href="#">Harga</a></li>
            <li><a href="#">Keamanan</a></li>
            <li><a href="#">Roadmap</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h5>Perusahaan</h5>
          <ul>
            <li><a href="#">Tentang Kami</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">Karir</a></li>
            <li><a href="#">Partner</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h5>Dukungan</h5>
          <ul>
            <li><a href="#">Dokumentasi</a></li>
            <li><a href="#">Pusat Bantuan</a></li>
            <li><a href="#">Kontak</a></li>
            <li><a href="#">Status Sistem</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <span class="footer-copy">© 2025 AsetKu. Hak cipta dilindungi.</span>
        <div class="footer-bl">
          <a href="#">Privasi</a>
          <a href="#">Syarat & Ketentuan</a>
        </div>
      </div>
    </div>
  </footer>

  <script>
    const io = new IntersectionObserver(entries => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('on'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => io.observe(el));
  </script>
</body>
</html>