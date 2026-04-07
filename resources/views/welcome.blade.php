<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>AsetKu — Manajemen Aset Modern</title>
  <!-- Inter & Plus Jakarta Sans -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Midtrans Snap (sandbox by default, production bisa disesuaikan) -->
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-YourKeyHere"></script>
  <style>
    * {
      font-family: 'Inter', 'Plus Jakarta Sans', system-ui, sans-serif;
    }
    body {
      background: #ffffff;
    }
    /* custom checkbox & card style */
    .license-card {
      transition: all 0.2s ease;
      cursor: pointer;
    }
    .license-card.selected {
      border-color: #2563eb;
      background: #eff6ff;
      box-shadow: 0 8px 20px -8px rgba(37,99,235,0.2);
      transform: scale(1.01);
    }
    .plan-badge {
      background: #eef2ff;
      color: #1e40af;
      font-size: 0.7rem;
      font-weight: 700;
      padding: 4px 12px;
      border-radius: 40px;
      display: inline-block;
    }
    .btn-primary {
      background: #2563eb;
      transition: all 0.2s;
    }
    .btn-primary:hover {
      background: #1d4ed8;
      transform: translateY(-1px);
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
    .modal-mask {
      background: rgba(0,0,0,0.5);
      backdrop-filter: blur(4px);
    }
    .loading-spinner {
      border: 2px solid #e2e8f0;
      border-top: 2px solid #2563eb;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      animation: spin 0.8s linear infinite;
      display: inline-block;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>
</head>
<body class="antialiased">

<!-- ======================= LANDING PAGE (NAV, HERO, LISENSI, CTA) ======================= -->
<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 py-4">
  <div class="max-w-7xl mx-auto px-6 lg:px-8 flex justify-between items-center">
    <div class="flex items-center gap-2">
      <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
      </div>
      <span class="text-xl font-extrabold tracking-tight text-slate-800">Aset<span class="text-indigo-600">Ku</span></span>
    </div>
    <div class="flex gap-4 items-center">
      <a href="#" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition">Login</a>
      <a href="#pricing" class="bg-indigo-600 text-white px-5 py-2 rounded-full text-sm font-semibold shadow-sm hover:bg-indigo-700 transition">Coba Gratis</a>
    </div>
  </div>
</nav>

<main>
  <!-- Hero Section -->
  <section class="py-16 lg:py-24 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div class="reveal">
          <div class="inline-flex items-center gap-2 bg-blue-50 rounded-full px-4 py-1.5 text-blue-700 text-xs font-semibold mb-6">
            ⚡ Platform Aset Terintegrasi
          </div>
          <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900 leading-tight">Kelola Aset Instansi <br><span class="text-indigo-600">Lebih Cerdas & Real-time</span></h1>
          <p class="text-slate-500 mt-6 text-lg max-w-md">Pantau inventaris, lacak lokasi, laporan instan — dalam satu dasbor modern tanpa batasan.</p>
          <div class="flex gap-4 mt-8">
            <a href="#pricing" class="bg-indigo-600 text-white px-6 py-3 rounded-full font-semibold shadow-md hover:bg-indigo-700 transition">Mulai 14 Hari Gratis →</a>
            <a href="#fitur" class="border border-slate-200 text-slate-700 px-6 py-3 rounded-full font-medium hover:bg-slate-50 transition">Lihat Demo</a>
          </div>
          <div class="flex gap-8 mt-10 pt-6 border-t border-slate-100">
            <div><span class="font-black text-2xl text-slate-800">2.800+</span><p class="text-xs text-slate-500">Instansi Aktif</p></div>
            <div><span class="font-black text-2xl text-slate-800">99%</span><p class="text-xs text-slate-500">Kepuasan Klien</p></div>
            <div><span class="font-black text-2xl text-slate-800">500K+</span><p class="text-xs text-slate-500">Aset Terkelola</p></div>
          </div>
        </div>
        <div class="reveal bg-slate-50 rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
          <div class="bg-white px-4 py-2 border-b border-slate-100 flex items-center gap-2">
            <div class="flex gap-1.5"><span class="w-3 h-3 rounded-full bg-red-400"></span><span class="w-3 h-3 rounded-full bg-yellow-400"></span><span class="w-3 h-3 rounded-full bg-green-400"></span></div>
            <div class="text-xs font-mono text-slate-400 bg-slate-100 px-3 py-0.5 rounded-full">dasbor.asetku.id/overview</div>
          </div>
          <div class="p-5 grid grid-cols-3 gap-0 text-sm">
            <div class="p-3"><div class="text-xs font-bold text-slate-400">TOTAL ASET</div><div class="text-2xl font-black text-blue-600">1.248</div><div class="text-[11px] text-green-600">↑ +3.2%</div></div>
            <div class="p-3"><div class="text-xs font-bold text-slate-400">KONDISI BAIK</div><div class="text-2xl font-black text-emerald-600">1.101</div></div>
            <div class="p-3"><div class="text-xs font-bold text-slate-400">PERLU RAWAT</div><div class="text-2xl font-black text-amber-600">147</div></div>
          </div>
          <div class="border-t border-slate-100 divide-y divide-slate-100">
            <div class="flex justify-between px-5 py-3"><span class="font-semibold">MacBook Pro M3</span><span class="text-slate-500 text-xs">IT Dept</span><span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Operasional</span></div>
            <div class="flex justify-between px-5 py-3"><span class="font-semibold">Printer Canon MX498</span><span class="text-slate-500 text-xs">Admin Lt1</span><span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">Perlu Servis</span></div>
            <div class="flex justify-between px-5 py-3"><span class="font-semibold">Proyektor Epson EB</span><span class="text-slate-500 text-xs">Aula Utama</span><span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Aktif</span></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- LISENSI / PAKET (Per Bulan & Per Tahun) seperti gambar card modern -->
  <section id="pricing" class="py-20 bg-gradient-to-b from-white to-slate-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-12 reveal">
        <span class="text-indigo-600 font-bold text-sm tracking-wider">PAKET LISENSI</span>
        <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-800 mt-2">Pilih sesuai kebutuhan instansi Anda</h2>
        <p class="text-slate-500 mt-4">Tagihan per bulan atau hemat 20% dengan bayar per tahun. Semua paket sudah termasuk fitur lengkap.</p>
      </div>

      <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <!-- Card Bulanan -->
        <div id="planMonthlyCard" class="license-card rounded-2xl border-2 border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition-all cursor-pointer">
          <div class="flex justify-between items-start">
            <div><span class="plan-badge">FLEKSIBEL</span></div>
            <div class="text-indigo-600 font-bold text-sm">✨ POPULER</div>
          </div>
          <div class="mt-4"><span class="text-4xl font-black text-slate-800">Rp49.000</span><span class="text-slate-500"> / bulan</span></div>
          <p class="text-slate-500 text-sm mt-1">Tagihan bulanan, bebas putus kapan saja</p>
          <ul class="mt-6 space-y-3 text-sm">
            <li class="flex gap-2"><span>✅</span> Akses penuh manajemen aset</li>
            <li class="flex gap-2"><span>✅</span> Unlimited proyek & lokasi</li>
            <li class="flex gap-2"><span>✅</span> Laporan real-time & ekspor</li>
            <li class="flex gap-2"><span>✅</span> Dukungan prioritas email</li>
          </ul>
          <div class="mt-8"><div class="text-xs text-slate-400">*Pajak tidak termasuk</div></div>
        </div>

        <!-- Card Tahunan (lebih hemat) -->
        <div id="planYearlyCard" class="license-card rounded-2xl border-2 border-indigo-200 bg-white p-6 shadow-md hover:shadow-lg transition-all cursor-pointer relative">
          <div class="absolute -top-3 left-6 bg-indigo-600 text-white text-[11px] font-bold px-3 py-1 rounded-full">HEMAT 20%</div>
          <div class="flex justify-between items-start">
            <div><span class="plan-badge" style="background:#e0e7ff;">BEST VALUE</span></div>
          </div>
          <div class="mt-4"><span class="text-4xl font-black text-slate-800">Rp470.000</span><span class="text-slate-500"> / tahun</span></div>
          <p class="text-slate-500 text-sm mt-1">Setara Rp39.200/bulan — hemat Rp118.000/tahun</p>
          <ul class="mt-6 space-y-3 text-sm">
            <li class="flex gap-2"><span>✅</span> Semua fitur bulanan +</li>
            <li class="flex gap-2"><span>✅</span> 2 akun admin tambahan gratis</li>
            <li class="flex gap-2"><span>✅</span> Audit trail 12 bulan</li>
            <li class="flex gap-2"><span>✅</span> Dukungan WhatsApp prioritas</li>
          </ul>
          <div class="mt-8"><div class="text-xs text-slate-400">*Pajak tidak termasuk</div></div>
        </div>
      </div>

      <div class="text-center mt-10 reveal">
        <button id="selectPlanBtn" class="bg-indigo-600 text-white px-8 py-3 rounded-full font-bold shadow-lg hover:bg-indigo-700 transition text-lg">Daftar & Pilih Paket →</button>
        <p class="text-xs text-slate-400 mt-3">Garansi 14 hari uang kembali, tanpa ribet</p>
      </div>
    </div>
  </section>

  <!-- Fitur tambahan ringkas -->
  <section id="fitur" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="grid md:grid-cols-3 gap-8 text-center reveal">
        <div><div class="text-4xl mb-3">📍</div><h3 class="font-bold">Lacak Lokasi Real-time</h3><p class="text-slate-500 text-sm">Filter gedung, ruangan, divisi</p></div>
        <div><div class="text-4xl mb-3">📊</div><h3 class="font-bold">Dashboard Analitik</h3><p class="text-slate-500 text-sm">Grafik depresiasi & status aset</p></div>
        <div><div class="text-4xl mb-3">🔔</div><h3 class="font-bold">Pengingat Servis</h3><p class="text-slate-500 text-sm">Otomatis jadwal kalibrasi</p></div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="py-16 bg-slate-900 text-white rounded-t-3xl">
    <div class="max-w-4xl mx-auto text-center px-6 reveal">
      <h2 class="text-3xl font-bold">Siap digitalisasi pengelolaan aset?</h2>
      <p class="text-slate-300 mt-3">Pilih paket langganan dan dapatkan akses penuh dalam 2 menit</p>
      <button id="ctaBottomBtn" class="mt-6 bg-white text-slate-900 px-8 py-3 rounded-full font-bold hover:bg-slate-100 transition">Mulai Sekarang</button>
    </div>
  </section>
</main>

<footer class="bg-white border-t border-slate-100 py-10 text-center text-slate-400 text-sm">
  <div class="max-w-7xl mx-auto">© 2025 AsetKu. Seluruh hak cipta. Manajemen Aset Modern.</div>
</footer>

<!-- ======================= MODAL REGISTRASI + PEMBAYARAN (otomatis sesuai pilihan perbulan / pertahun) ======================= -->
<div id="registerModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 modal-mask transition-all">
  <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden transform transition-all">
    <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100 flex justify-between items-center">
      <h3 class="font-bold text-slate-800 text-lg">Daftar Akun Admin Sekolah</h3>
      <button id="closeModalBtn" class="text-slate-400 hover:text-slate-600 text-2xl leading-5">&times;</button>
    </div>
    <div class="p-6">
      <div id="modalAlert" class="hidden mb-4 p-3 rounded-lg text-sm"></div>
      <!-- pilihan paket yang dipilih (dinamis) -->
      <div class="bg-slate-50 p-3 rounded-xl mb-5 flex justify-between items-center">
        <span class="font-semibold text-slate-700">Paket dipilih:</span>
        <span id="selectedPlanLabel" class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-bold">Bulanan (Rp49.000/bulan)</span>
      </div>
      <form id="registerForm">
        @csrf
        <div class="space-y-4">
          <div><label class="block text-xs font-bold text-slate-600">Nama Lengkap</label><input type="text" id="regName" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nama Kepala Sekolah / Admin"></div>
          <div><label class="block text-xs font-bold text-slate-600">Email</label><input type="email" id="regEmail" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm" placeholder="admin@sekolah.sch.id"></div>
          <div><label class="block text-xs font-bold text-slate-600">No. HP (opsional)</label><input type="text" id="regPhone" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm" placeholder="0812xxxx"></div>
          <div><label class="block text-xs font-bold text-slate-600">Password</label><input type="password" id="regPassword" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm" placeholder="Min. 8 karakter"></div>
          <div><label class="block text-xs font-bold text-slate-600">Konfirmasi Password</label><input type="password" id="regPasswordConf" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm"></div>
          <div class="flex items-center gap-2"><input type="checkbox" id="termsCheckbox" class="rounded border-slate-300"><span class="text-xs text-slate-500">Saya setuju dengan <a href="#" class="text-indigo-600">Syarat & Ketentuan</a></span></div>
        </div>
        <div class="mt-6">
          <button type="button" id="processPaymentBtn" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition flex justify-center items-center gap-2">Bayar Langganan <span id="paymentAmountSpan">Rp49.000</span></button>
        </div>
        <p class="text-center text-[11px] text-slate-400 mt-4">Pembayaran aman via Midtrans (kartu, transfer, e-wallet)</p>
      </form>
    </div>
  </div>
</div>

<script>
  // STATE: pilihan paket (monthly / yearly)
  let selectedPlan = 'monthly';   // monthly: 49.000 , yearly: 470.000
  const monthlyAmount = 49000;
  const yearlyAmount = 470000;

  // UI Cards Highlight
  const monthlyCard = document.getElementById('planMonthlyCard');
  const yearlyCard = document.getElementById('planYearlyCard');
  const selectPlanBtn = document.getElementById('selectPlanBtn');
  const ctaBottomBtn = document.getElementById('ctaBottomBtn');
  const modal = document.getElementById('registerModal');
  const closeModal = document.getElementById('closeModalBtn');
  const selectedPlanLabel = document.getElementById('selectedPlanLabel');
  const paymentAmountSpan = document.getElementById('paymentAmountSpan');

  function updateSelectedPlanUI() {
    if (selectedPlan === 'monthly') {
      monthlyCard.classList.add('selected', 'border-indigo-400', 'bg-indigo-50/40');
      yearlyCard.classList.remove('selected', 'border-indigo-400', 'bg-indigo-50/40');
      yearlyCard.classList.add('border-slate-200');
      selectedPlanLabel.innerText = 'Bulanan (Rp49.000/bulan)';
      paymentAmountSpan.innerText = 'Rp49.000';
    } else {
      yearlyCard.classList.add('selected', 'border-indigo-400', 'bg-indigo-50/40');
      monthlyCard.classList.remove('selected', 'border-indigo-400', 'bg-indigo-50/40');
      monthlyCard.classList.add('border-slate-200');
      selectedPlanLabel.innerText = 'Tahunan (Rp470.000/tahun) Hemat 20%';
      paymentAmountSpan.innerText = 'Rp470.000';
    }
  }

  monthlyCard.addEventListener('click', () => { selectedPlan = 'monthly'; updateSelectedPlanUI(); });
  yearlyCard.addEventListener('click', () => { selectedPlan = 'yearly'; updateSelectedPlanUI(); });
  
  function openModal() { modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden'; }
  function closeModalFunc() { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; }
  selectPlanBtn.addEventListener('click', openModal);
  ctaBottomBtn.addEventListener('click', openModal);
  closeModal.addEventListener('click', closeModalFunc);
  modal.addEventListener('click', (e) => { if(e.target === modal) closeModalFunc(); });

  // Validasi dan proses pembayaran via Midtrans
  const processBtn = document.getElementById('processPaymentBtn');
  const alertDiv = document.getElementById('modalAlert');

  function showAlert(msg, isError = true) {
    alertDiv.classList.remove('hidden');
    alertDiv.innerHTML = msg;
    alertDiv.className = `mb-4 p-3 rounded-lg text-sm ${isError ? 'bg-red-50 text-red-700 border-l-4 border-red-500' : 'bg-green-50 text-green-700 border-l-4 border-green-500'}`;
    setTimeout(() => { alertDiv.classList.add('hidden'); }, 4000);
  }

  // Fungsi untuk mendapatkan CSRF token dengan benar
  function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
      console.warn('CSRF token not found, trying to get from cookie');
      // Fallback: ambil dari cookie Laravel
      const name = 'XSRF-TOKEN';
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
    }
    return token;
  }

  processBtn.addEventListener('click', async () => {
    const name = document.getElementById('regName').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    const phone = document.getElementById('regPhone').value.trim();
    const password = document.getElementById('regPassword').value;
    const passwordConf = document.getElementById('regPasswordConf').value;
    const terms = document.getElementById('termsCheckbox').checked;

    if (!name || !email || !password || !passwordConf) {
      showAlert('Harap isi nama, email, dan password.');
      return;
    }
    if (!/^[^\s@]+@([^\s@]+\.)+[^\s@]+$/.test(email)) {
      showAlert('Email tidak valid.');
      return;
    }
    if (password.length < 8) {
      showAlert('Password minimal 8 karakter.');
      return;
    }
    if (password !== passwordConf) {
      showAlert('Password dan konfirmasi tidak cocok.');
      return;
    }
    if (!terms) {
      showAlert('Anda harus menyetujui syarat & ketentuan.');
      return;
    }

    // set loading
    const originalText = processBtn.innerHTML;
    processBtn.disabled = true;
    processBtn.innerHTML = '<span class="loading-spinner"></span> Memproses...';

    const amount = selectedPlan === 'monthly' ? monthlyAmount : yearlyAmount;
    const planType = selectedPlan === 'monthly' ? 'monthly' : 'yearly';
    
    try {
      const csrfToken = getCsrfToken();
      
      if (!csrfToken) {
        throw new Error('Token CSRF tidak ditemukan. Silakan refresh halaman.');
      }

      const response = await fetch('{{ route("register.payment.token") }}', {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json', 
          'X-CSRF-TOKEN': csrfToken, 
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        credentials: 'same-origin', // Penting untuk mengirim cookie
        body: JSON.stringify({ 
          name, 
          email, 
          phone, 
          password, 
          password_confirmation: passwordConf,
          amount, 
          plan_type: planType 
        })
      });
      
      // Handle response tidak OK
      if (!response.ok) {
        const errorText = await response.text();
        console.error('Response error:', errorText);
        throw new Error(`Server error: ${response.status}`);
      }
      
      const data = await response.json();
      
      if (!data.snap_token) {
        throw new Error(data.message || 'Gagal membuat token pembayaran.');
      }
      
      // Open snap popup
      if (typeof snap === 'undefined') {
        throw new Error('Midtrans Snap tidak tersedia. Silakan refresh halaman.');
      }
      
      window.snap.pay(data.snap_token, {
        onSuccess: async (result) => {
          await finalizeRegistration(name, email, phone, password, passwordConf, result.order_id, 'success', planType, amount);
        },
        onPending: async (result) => {
          await finalizeRegistration(name, email, phone, password, passwordConf, result.order_id, 'pending', planType, amount);
        },
        onError: (result) => {
          showAlert('Pembayaran gagal: ' + (result.status_message || 'Silakan coba lagi'));
          processBtn.disabled = false;
          processBtn.innerHTML = originalText;
        },
        onClose: () => {
          processBtn.disabled = false;
          processBtn.innerHTML = originalText;
        }
      });
    } catch (err) {
      console.error('Payment error:', err);
      showAlert(err.message || 'Koneksi gagal. Pastikan backend payment token tersedia.');
      processBtn.disabled = false;
      processBtn.innerHTML = originalText;
    }
  });

  async function finalizeRegistration(name, email, phone, password, passwordConf, orderId, txStatus, planType, amount) {
      const csrfToken = getCsrfToken();
      
      try {
          const finalRes = await fetch('{{ route("register.final") }}', {
              method: 'POST',
              headers: { 
                  'Content-Type': 'application/json', 
                  'X-CSRF-TOKEN': csrfToken,
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json'
              },
              credentials: 'same-origin',
              body: JSON.stringify({
                  name, 
                  email, 
                  phone, 
                  password, 
                  password_confirmation: passwordConf,
                  order_id: orderId, 
                  transaction_status: txStatus, 
                  plan_type: planType, 
                  amount: amount, 
                  role: 'admin_sekolah'
              })
          });
          
          const finalData = await finalRes.json();
          
          if (finalData.success) {
              showAlert('Pendaftaran sukses! Lisensi aktif. Silakan login.', false);
              setTimeout(() => { 
                  window.location.href = finalData.redirect || '{{ route("login") }}'; 
              }, 2000);
          } else {
              showAlert(finalData.message || 'Gagal menyimpan data, hubungi support.');
              document.getElementById('processPaymentBtn').disabled = false;
              document.getElementById('processPaymentBtn').innerHTML = `Bayar Langganan <span>${selectedPlan === 'monthly' ? 'Rp49.000' : 'Rp470.000'}</span>`;
          }
      } catch (err) {
          console.error('Final registration error:', err);
          showAlert('Error saat registrasi final: ' + err.message);
          document.getElementById('processPaymentBtn').disabled = false;
          document.getElementById('processPaymentBtn').innerHTML = `Bayar Langganan <span>${selectedPlan === 'monthly' ? 'Rp49.000' : 'Rp470.000'}</span>`;
      }
  }

  // Reveal on scroll
  const reveals = document.querySelectorAll('.reveal');
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if(e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.1 });
  reveals.forEach(r => obs.observe(r));

  // default selected monthly UI
  updateSelectedPlanUI();
</script>
</body>
</html>