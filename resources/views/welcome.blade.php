<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>AsetKu — Manajemen Aset Modern & Lebar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background: #ffffff
      color: #171717;
      line-height: 1.5;
      scroll-behavior: smooth;
    }

    /* LEBAR — max-width lebih besar, padding lebih lega */
    .container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 32px;
    }

    /* navbar lebih luas */
    .navbar {
      position: sticky;
      top: 0;
      background: rgba(255, 255, 255, 0.96);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid #eef2f6;
      z-index: 50;
      padding: 16px 0;
    }

    .nav-flex {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 20px;
    }

    .logo {
      font-weight: 800;
      font-size: 1.5rem;
      letter-spacing: -0.02em;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      background-clip: text;
      -webkit-background-clip: text;
      color: transparent;
    }

    .nav-links {
      display: flex;
      gap: 40px;
      align-items: center;
    }

    .nav-links a {
      text-decoration: none;
      font-size: 0.95rem;
      font-weight: 500;
      color: #334155;
      transition: color 0.2s;
    }

    .nav-links a:hover {
      color: #2563eb;
    }

    .btn-outline-light {
      background: transparent;
      border: 1px solid #e2e8f0;
      padding: 8px 20px;
      border-radius: 40px;
      font-weight: 600;
      font-size: 0.85rem;
      color: #1e293b;
      cursor: pointer;
      transition: all 0.2s;
      text-decoration: none;
    }

    .btn-outline-light:hover {
      border-color: #2563eb;
      background: #f8fafc;
    }

    .btn-primary {
      background: #2563eb;
      color: white;
      border: none;
      padding: 8px 22px;
      border-radius: 40px;
      font-weight: 600;
      font-size: 0.85rem;
      cursor: pointer;
      transition: background 0.2s;
      text-decoration: none;
    }

    .btn-primary:hover {
      background: #1d4ed8;
    }

    /* HERO — layout lebih lebar, tidak terlalu dipusatkan secara kaku */
    .hero {
      padding: 72px 0 64px;
    }

    .hero-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 48px;
      align-items: center;
    }

    .hero-left {
      max-width: 100%;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #eff6ff;
      padding: 6px 18px;
      border-radius: 40px;
      font-size: 0.75rem;
      font-weight: 600;
      color: #2563eb;
      margin-bottom: 24px;
    }

    .hero-left h1 {
      font-size: clamp(2.2rem, 4vw, 3.5rem);
      font-weight: 800;
      letter-spacing: -0.02em;
      line-height: 1.2;
      margin-bottom: 20px;
      color: #0f172a;
    }

    .hero-left h1 span {
      color: #2563eb;
    }

    .hero-desc {
      font-size: 1rem;
      color: #475569;
      max-width: 480px;
      margin-bottom: 32px;
    }

    .hero-actions {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 40px;
    }

    .btn-secondary {
      background: #f1f5f9;
      color: #1e293b;
      padding: 12px 28px;
      border-radius: 40px;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.2s;
    }

    .btn-secondary:hover {
      background: #e2e8f0;
    }

    /* stats row lebih lebar & natural */
    .stats-row {
      display: flex;
      gap: 48px;
      flex-wrap: wrap;
      border-top: 1px solid #eef2f6;
      padding-top: 32px;
    }

    .stat-item h3 {
      font-size: 1.8rem;
      font-weight: 800;
      color: #0f172a;
      line-height: 1.2;
    }

    .stat-item p {
      font-size: 0.8rem;
      color: #5b6e8c;
      font-weight: 500;
    }

    /* preview card - full lebar tapi rapi */
    .preview-card {
      background: #ffffff;
      border-radius: 28px;
      box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.06), 0 0 0 1px rgba(0, 0, 0, 0.02);
      overflow: hidden;
      border: 1px solid #edf2f7;
    }

    .preview-header {
      background: #fefefe;
      padding: 14px 24px;
      border-bottom: 1px solid #eef2f6;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .mock-dots {
      display: flex;
      gap: 6px;
    }

    .dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #cbd5e1;
    }

    .dot.red { background: #ef4444; }
    .dot.yellow { background: #f59e0b; }
    .dot.green { background: #10b981; }

    .mock-url {
      font-size: 0.7rem;
      font-family: monospace;
      color: #64748b;
      background: #f1f5f9;
      padding: 4px 14px;
      border-radius: 30px;
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1px;
      background: #f1f5f9;
    }

    .stat-box {
      background: white;
      padding: 24px 20px;
    }

    .stat-label-sm {
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      color: #5b6e8c;
      letter-spacing: 0.04em;
      margin-bottom: 8px;
    }

    .stat-value {
      font-size: 2.2rem;
      font-weight: 800;
      color: #0f172a;
    }

    .stat-value.blue { color: #2563eb; }
    .stat-value.green { color: #16a34a; }
    .stat-value.amber { color: #d97706; }

    .asset-list {
      grid-column: span 3;
      background: white;
      padding: 8px 0;
    }

    .list-row {
      display: grid;
      grid-template-columns: 2fr 1fr auto;
      padding: 14px 24px;
      border-bottom: 1px solid #f0f2f5;
      align-items: center;
    }

    .list-row:last-child {
      border-bottom: none;
    }

    .asset-name {
      font-weight: 700;
      font-size: 0.9rem;
    }

    .asset-loc {
      font-size: 0.75rem;
      color: #5b6e8c;
      margin-top: 2px;
    }

    .status-badge {
      font-size: 0.7rem;
      font-weight: 600;
      padding: 4px 14px;
      border-radius: 30px;
      background: #e6f7ec;
      color: #15803d;
    }

    .status-badge.warning {
      background: #fffbeb;
      color: #b45309;
    }

    /* features grid lebih lebar */
    .section {
      padding: 96px 0;
    }

    .section-header {
      max-width: 700px;
      margin-bottom: 64px;
    }

    .section-label {
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: #2563eb;
      margin-bottom: 12px;
    }

    .section-title {
      font-size: clamp(1.8rem, 3.5vw, 2.5rem);
      font-weight: 800;
      color: #0f172a;
      margin-bottom: 16px;
    }

    .section-sub {
      color: #475569;
      font-size: 1rem;
      max-width: 560px;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(310px, 1fr));
      gap: 32px;
    }

    .feature-item {
      background: #ffffff;
      border: 1px solid #edf2f7;
      border-radius: 28px;
      padding: 32px;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .feature-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 24px 36px -12px rgba(0, 0, 0, 0.08);
      border-color: #e2e8f0;
    }

    .feature-icon {
      font-size: 2rem;
      margin-bottom: 24px;
    }

    .feature-title {
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .feature-desc {
      font-size: 0.85rem;
      color: #475569;
      line-height: 1.5;
    }

    /* testimoni grid lebar */
    .testi-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 32px;
    }

    .testi-card {
      background: #f9fafb;
      border-radius: 28px;
      padding: 32px;
      border: 1px solid #f0f2f5;
    }

    .stars {
      color: #f59e0b;
      font-size: 0.9rem;
      letter-spacing: 2px;
      margin-bottom: 20px;
    }

    .testi-text {
      font-size: 0.95rem;
      color: #1e293b;
      margin-bottom: 24px;
      line-height: 1.6;
    }

    .testi-author {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .avatar {
      width: 44px;
      height: 44px;
      background: #e2e8f0;
      border-radius: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: #0f172a;
    }

    .author-name {
      font-weight: 700;
      font-size: 0.9rem;
    }

    .author-role {
      font-size: 0.7rem;
      color: #5b6e8c;
    }

    /* CTA lebar tapi proporsional */
    .cta-block {
      background: #0f172a;
      border-radius: 32px;
      padding: 64px 48px;
      text-align: center;
      color: white;
    }

    .cta-title {
      font-size: clamp(1.8rem, 3vw, 2.3rem);
      font-weight: 700;
      margin-bottom: 18px;
    }

    .cta-desc {
      max-width: 560px;
      margin: 0 auto 32px;
      color: #cbd5e1;
    }

    .cta-buttons {
      display: flex;
      gap: 20px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn-white {
      background: white;
      color: #0f172a;
      padding: 14px 34px;
      border-radius: 50px;
      font-weight: 700;
      text-decoration: none;
    }

    .btn-outline-white {
      border: 1px solid rgba(255, 255, 255, 0.3);
      background: transparent;
      color: white;
      padding: 14px 34px;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
    }

    /* footer lebar */
    footer {
      background: #ffffff;
      border-top: 1px solid #edf2f7;
      padding: 64px 0 40px;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1.5fr;
      gap: 48px;
      margin-bottom: 56px;
    }

    .footer-logo {
      font-weight: 800;
      font-size: 1.3rem;
      color: #0f172a;
      margin-bottom: 14px;
    }

    .footer-desc {
      font-size: 0.85rem;
      color: #475569;
      max-width: 260px;
    }

    .footer-col h4 {
      font-size: 0.9rem;
      font-weight: 700;
      margin-bottom: 20px;
      color: #0f172a;
    }

    .footer-col ul {
      list-style: none;
    }

    .footer-col li {
      margin-bottom: 12px;
    }

    .footer-col a {
      text-decoration: none;
      font-size: 0.85rem;
      color: #5b6e8c;
      transition: color 0.2s;
    }

    .footer-col a:hover {
      color: #2563eb;
    }

    .footer-bottom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 20px;
      padding-top: 28px;
      border-top: 1px solid #edf2f7;
      font-size: 0.8rem;
      color: #64748b;
    }

    .footer-links {
      display: flex;
      gap: 32px;
    }

    /* responsive lebar lebih nyaman */
    @media (max-width: 1100px) {
      .hero-grid {
        grid-template-columns: 1fr;
        gap: 48px;
      }
      .hero-left {
        max-width: 100%;
        text-align: left;
      }
      .stats-row {
        justify-content: flex-start;
      }
    }

    @media (max-width: 800px) {
      .container {
        padding: 0 24px;
      }
      .footer-grid {
        grid-template-columns: 1fr 1fr;
        gap: 40px;
      }
      .dashboard-grid {
        grid-template-columns: 1fr;
      }
      .asset-list {
        grid-column: span 1;
      }
      .hero-actions {
        justify-content: flex-start;
      }
    }

    @media (max-width: 640px) {
      .nav-links {
        display: none;
      }
      .footer-grid {
        grid-template-columns: 1fr;
      }
      .stats-row {
        flex-direction: column;
        gap: 24px;
      }
      .cta-block {
        padding: 48px 24px;
      }
    }

    .reveal {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body>

<nav class="navbar">
  <div class="container">
    <div class="nav-flex">
      <div class="logo">AsetKu</div>
      <div class="nav-links">
        <a href="{{ route('login') }}" class="btn-outline-light">Masuk</a>
        <a href="" class="btn-primary">Coba Gratis</a>
      </div>
    </div>
  </div>
</nav>

<main>
  <!-- Hero dengan layout 2 kolom lebih lebar & tidak terpusat -->
  <section class="hero">
    <div class="container">
      <div class="hero-grid">
        <div class="hero-left">
          <div class="badge reveal">
            ⚡ Platform Aset Terintegrasi
          </div>
          <h1 class="reveal">Kelola Aset Instansi <br><span>Lebih Cepat & Akurat</span></h1>
          <p class="hero-desc reveal">
            Pantau seluruh inventaris, lacak lokasi aset, dan buat laporan instan — semuanya dalam satu dasbor modern tanpa batasan.
          </p>
          <div class="hero-actions reveal">
            <a href="#cta" class="btn-primary" style="padding: 12px 32px;">Coba 14 Hari Gratis →</a>
            <a href="#features" class="btn-secondary">Lihat Demo</a>
          </div>
          <div class="stats-row reveal">
            <div class="stat-item">
              <h3>2.800+</h3>
              <p>Instansi Aktif</p>
            </div>
            <div class="stat-item">
              <h3>99%</h3>
              <p>Kepuasan Klien</p>
            </div>
            <div class="stat-item">
              <h3>500K+</h3>
              <p>Aset Terkelola</p>
            </div>
          </div>
        </div>
        <div class="hero-right reveal">
          <div class="preview-card">
            <div class="preview-header">
              <div class="mock-dots">
                <div class="dot red"></div>
                <div class="dot yellow"></div>
                <div class="dot green"></div>
              </div>
              <div class="mock-url">dasbor.asetku.id/overview</div>
            </div>
            <div class="dashboard-grid">
              <div class="stat-box">
                <div class="stat-label-sm">Total Aset</div>
                <div class="stat-value blue">1.248</div>
                <div style="font-size: 0.7rem; color:#3b7c0c;">↑ +3.2% bulan ini</div>
              </div>
              <div class="stat-box">
                <div class="stat-label-sm">Kondisi Baik</div>
                <div class="stat-value green">1.101</div>
                <div style="font-size: 0.7rem; color:#475569;">88% dari total</div>
              </div>
              <div class="stat-box">
                <div class="stat-label-sm">Perlu Perawatan</div>
                <div class="stat-value amber">147</div>
                <div style="font-size: 0.7rem; color:#d97706;">Segera tindaklanjuti</div>
              </div>
              <div class="asset-list">
                <div class="list-row">
                  <div>
                    <div class="asset-name">MacBook Pro M3</div>
                    <div class="asset-loc">IT Dept · Lantai 3</div>
                  </div>
                  <div>Kantor Pusat</div>
                  <div><span class="status-badge">Operasional</span></div>
                </div>
                <div class="list-row">
                  <div>
                    <div class="asset-name">Printer Canon MX498</div>
                    <div class="asset-loc">Administrasi · Lt 1</div>
                  </div>
                  <div>Gedung A</div>
                  <div><span class="status-badge warning">Perlu Servis</span></div>
                </div>
                <div class="list-row">
                  <div>
                    <div class="asset-name">Proyektor Epson EB</div>
                    <div class="asset-loc">Aula Utama</div>
                  </div>
                  <div>Gedung B</div>
                  <div><span class="status-badge">Aktif</span></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features -->
  <section id="features" class="section">
    <div class="container">
      <div class="section-header reveal">
        <div class="section-label">KEUNGGULAN PLATFORM</div>
        <h2 class="section-title">Semua fitur yang Anda butuhkan, tanpa kerumitan</h2>
        <p class="section-sub">Didesain untuk efisiensi, dari pencatatan hingga pelaporan strategis.</p>
      </div>
      <div class="features-grid">
        <div class="feature-item reveal">
          <div class="feature-icon">📊</div>
          <div class="feature-title">Dasbor real-time</div>
          <div class="feature-desc">Pantau status, nilai aset, dan tren penggunaan secara langsung dari tampilan yang intuitif.</div>
        </div>
        <div class="feature-item reveal">
          <div class="feature-icon">📍</div>
          <div class="feature-title">Lacak lokasi aset</div>
          <div class="feature-desc">Filter berdasarkan divisi, ruangan, atau gedung. Temukan aset dalam hitungan detik.</div>
        </div>
        <div class="feature-item reveal">
          <div class="feature-icon">🔔</div>
          <div class="feature-title">Pengingat otomatis</div>
          <div class="feature-desc">Jadwal servis, kalibrasi, dan peringatan garansi otomatis — tidak ada yang terlewat.</div>
        </div>
        <div class="feature-item reveal">
          <div class="feature-icon">📄</div>
          <div class="feature-title">Laporan instan</div>
          <div class="feature-desc">Export PDF / Excel satu klik, cocok untuk audit internal maupun eksternal.</div>
        </div>
        <div class="feature-item reveal">
          <div class="feature-icon">👥</div>
          <div class="feature-title">Multi-level akses</div>
          <div class="feature-desc">Kelola hak akses untuk tim, dari admin pusat hingga operator lapangan.</div>
        </div>
        <div class="feature-item reveal">
          <div class="feature-icon">🔒</div>
          <div class="feature-title">Keamanan enterprise</div>
          <div class="feature-desc">Enkripsi data, backup harian, dan autentikasi dua faktor untuk keamanan maksimal.</div>
        </div>
      </div>
    </div>
  </section>



  <!-- CTA -->
  <section id="cta" class="section">
    <div class="container">
      <div class="cta-block reveal">
        <h2 class="cta-title">Siap mengelola aset dengan lebih cerdas?</h2>
        <p class="cta-desc">Nikmati gratis 14 hari penuh. Tanpa kartu kredit, setup cepat, dan tim support siap membantu.</p>
        <div class="cta-buttons">
          <a href="#" class="btn-white">Daftar Sekarang →</a>
          <a href="#" class="btn-outline-white">Hubungi Tim Sales</a>
        </div>
      </div>
    </div>
  </section>
</main>

<footer>
  <div class="container">
    <div class="footer-grid">
      <div>
        <div class="footer-logo">AsetKu</div>
        <div class="footer-desc">Solusi manajemen aset modern untuk instansi pemerintah dan perusahaan di Indonesia.</div>
      </div>
      <div class="footer-col">
        <h4>Platform</h4>
        <ul>
          <li><a href="#">Fitur Unggulan</a></li>
          <li><a href="#">Harga & Paket</a></li>
          <li><a href="#">Keamanan Data</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Perusahaan</h4>
        <ul>
          <li><a href="#">Tentang Kami</a></li>
          <li><a href="#">Karir</a></li>
          <li><a href="#">Blog & Artikel</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Dukungan</h4>
        <ul>
          <li><a href="#">Pusat Bantuan</a></li>
          <li><a href="#">Hubungi Kontak</a></li>
          <li><a href="#">Dokumentasi API</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2025 AsetKu. Seluruh hak cipta dilindungi.</span>
      <div class="footer-links">
        <a href="#">Kebijakan Privasi</a>
        <a href="#">Syarat & Ketentuan</a>
      </div>
    </div>
  </div>
</footer>

<script>
  const revealElements = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.1, rootMargin: "0px 0px -20px 0px" });
  revealElements.forEach(el => observer.observe(el));
</script>
</body>
</html>