<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar — Asset Management</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Midtrans Snap JS --}}
    @if(config('services.midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

        body {
            background: #f0f4ff;
            background-image:
                radial-gradient(ellipse 80% 60% at 20% -10%, rgba(99,102,241,.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 110%, rgba(59,130,246,.10) 0%, transparent 60%);
            min-height: 100vh;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.04), 0 20px 40px -8px rgba(99,102,241,.10);
        }

        .inp {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            color: #111827;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
            background: #fff;
        }
        .inp:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.10); }
        .inp.err   { border-color: #ef4444; }

        .lbl   { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .hint  { font-size: 12px; color: #9ca3af; margin-top: 4px; }
        .err-msg { font-size: 12px; color: #ef4444; margin-top: 4px; }

        .pass-wrap { position: relative; }
        .pass-wrap .inp { padding-right: 42px; }
        .pass-eye {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 0; color: #9ca3af;
        }
        .pass-eye:hover { color: #6366f1; }

        .strength-wrap { height: 4px; border-radius: 4px; background: #f1f5f9; margin-top: 8px; overflow: hidden; }
        .strength-fill { height: 100%; width: 0; border-radius: 4px; transition: width .3s, background .3s; }

        .btn-primary {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 13px;
            background: #6366f1; color: #fff;
            border: none; border-radius: 10px;
            font-size: 14px; font-weight: 700; cursor: pointer;
            transition: background .15s, transform .1s, box-shadow .15s;
        }
        .btn-primary:hover    { background: #4f46e5; box-shadow: 0 4px 14px rgba(99,102,241,.30); }
        .btn-primary:active   { transform: scale(.98); }
        .btn-primary:disabled { background: #c7d2fe; cursor: not-allowed; transform: none; box-shadow: none; }

        .alert-err {
            background: #fef2f2; border-left: 3px solid #ef4444;
            border-radius: 10px; padding: 12px 16px; font-size: 13px; color: #b91c1c;
        }
        .alert-ok {
            background: #f0fdf4; border-left: 3px solid #22c55e;
            border-radius: 10px; padding: 12px 16px; font-size: 13px; color: #15803d;
        }

        .section-head {
            font-size: 11px; font-weight: 700; letter-spacing: .08em;
            text-transform: uppercase; color: #9ca3af;
            padding-bottom: 10px; border-bottom: 1px solid #f1f5f9; margin-bottom: 16px;
        }

        .pay-info {
            background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 14px 16px;
        }

        .fade-up { animation: fadeUp .3s ease; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }

        @keyframes spin { to { transform: rotate(360deg); } }
        .spin { animation: spin .8s linear infinite; display:inline-block; }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .loading-content {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen py-10 px-4">
<div class="w-full max-w-md fade-up">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center gap-2 mb-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-500 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="text-xl text-gray-900" style="font-weight:800">Asset<span class="text-indigo-500">.</span></span>
        </div>
        <p class="text-sm text-gray-500">Daftar sebagai Admin Sekolah</p>
    </div>

    <div class="card p-8">

        {{-- Alerts --}}
        @if($errors->any())
        <div class="alert-err mb-6">
            <p style="font-weight:700">Registrasi gagal:</p>
            <ul class="list-disc list-inside mt-1 space-y-0.5">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
        @endif

        @if(session('success'))
        <div class="alert-ok mb-6">{{ session('success') }}</div>
        @endif

        @if(session('info'))
        <div class="alert-ok mb-6">{{ session('info') }}</div>
        @endif

        {{-- ── Data Akun ──────────────────────────────── --}}
        <p class="section-head">Informasi Akun</p>

        <div class="space-y-4 mb-6">

            <div>
                <label class="lbl">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="f_name"
                       class="inp @error('name') err @enderror"
                       placeholder="Nama lengkap Anda"
                       value="{{ old('name') }}">
                @error('name')<p class="err-msg">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="lbl">Email <span class="text-red-500">*</span></label>
                <input type="email" id="f_email"
                       class="inp @error('email') err @enderror"
                       placeholder="nama@email.com"
                       value="{{ old('email') }}">
                @error('email')<p class="err-msg">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="lbl">
                    No. HP
                    <span class="text-gray-400 font-normal text-xs ml-1">(opsional)</span>
                </label>
                <input type="text" id="f_phone"
                       class="inp @error('phone') err @enderror"
                       placeholder="08xxxxxxxxxx"
                       value="{{ old('phone') }}">
                @error('phone')<p class="err-msg">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="lbl">Password <span class="text-red-500">*</span></label>
                <div class="pass-wrap">
                    <input type="password" id="f_password"
                           class="inp @error('password') err @enderror"
                           placeholder="Minimal 8 karakter">
                    <button type="button" class="pass-eye" onclick="togglePw('f_password','eyePw1')">
                        <svg id="eyePw1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <div class="strength-wrap"><div class="strength-fill" id="strFill"></div></div>
                <p class="hint">Gabungkan huruf besar, kecil, angka, dan simbol</p>
                @error('password')<p class="err-msg">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="lbl">Konfirmasi Password <span class="text-red-500">*</span></label>
                <div class="pass-wrap">
                    <input type="password" id="f_pwconf"
                           class="inp"
                           placeholder="Ulangi password">
                    <button type="button" class="pass-eye" onclick="togglePw('f_pwconf','eyePw2')">
                        <svg id="eyePw2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <div id="pwMatchMsg"></div>
            </div>
        </div>

        {{-- ── Syarat ──────────────────────────────────── --}}
        <label class="flex items-start gap-3 cursor-pointer mb-6">
            <input type="checkbox" id="f_terms"
                   class="mt-0.5 w-4 h-4 rounded text-indigo-500 border-gray-300 focus:ring-indigo-500">
            <span class="text-sm text-gray-600">
                Saya menyetujui
                <a href="#" class="text-indigo-500 hover:underline" style="font-weight:600">Syarat &amp; Ketentuan</a>
                serta
                <a href="#" class="text-indigo-500 hover:underline" style="font-weight:600">Kebijakan Privasi</a>
            </span>
        </label>

        {{-- ── Info pembayaran ─────────────────────────── --}}
        <div class="pay-info mb-6">
            <div class="flex items-center gap-2 mb-1.5"
                 style="font-size:13px;font-weight:700;color:#1d4ed8">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Lisensi Rp&nbsp;500.000&nbsp;/&nbsp;tahun
            </div>
            <p style="font-size:12px;color:#1e40af;line-height:1.6">
                Popup pembayaran akan muncul setelah klik tombol di bawah.
                Lisensi aktif otomatis setelah pembayaran berhasil.
                Data sekolah bisa dilengkapi di halaman profil.
            </p>
        </div>

        {{-- Submit --}}
        <button type="button" onclick="handleSubmit()" id="btnSubmit" class="btn-primary">
            <svg id="btnIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <span id="btnLabel">Daftar &amp; Bayar</span>
        </button>

        {{-- Hidden form untuk final submit setelah Midtrans sukses --}}
        <form method="POST" action="{{ route('register.final') }}" id="finalForm" style="display:none">
            @csrf
            <input type="hidden" name="role" value="admin_sekolah">
            <input type="hidden" name="name" id="hName">
            <input type="hidden" name="email" id="hEmail">
            <input type="hidden" name="phone" id="hPhone">
            <input type="hidden" name="password" id="hPw">
            <input type="hidden" name="password_confirmation" id="hPwC">
            <input type="hidden" name="order_id" id="hOrderId">
            <input type="hidden" name="transaction_status" id="hTxStatus">
        </form>

    </div>

    <p class="text-center text-sm text-gray-500 mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-indigo-500 hover:underline" style="font-weight:600">Masuk sekarang</a>
    </p>
    <p class="text-center text-xs text-gray-400 mt-4">&copy; {{ date('Y') }} Asset Management</p>
</div>

<script>
const EYE_OPEN  = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
const EYE_SLASH = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';

function togglePw(inputId, iconId) {
    const el   = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    const show = el.type === 'password';
    el.type         = show ? 'text' : 'password';
    icon.innerHTML  = show ? EYE_SLASH : EYE_OPEN;
}

/* Strength bar */
document.getElementById('f_password').addEventListener('input', function () {
    const v = this.value;
    let s = 0;
    if (v.length >= 8)           s++;
    if (/[a-z]/.test(v))         s++;
    if (/[A-Z]/.test(v))         s++;
    if (/[0-9]/.test(v))         s++;
    if (/[^a-zA-Z0-9]/.test(v))  s++;
    const f = document.getElementById('strFill');
    f.style.width      = [0,20,40,60,80,100][s] + '%';
    f.style.background = ['','#ef4444','#f59e0b','#eab308','#3b82f6','#22c55e'][s];
});

/* Match indicator */
document.getElementById('f_pwconf').addEventListener('input', function () {
    const pw = document.getElementById('f_password').value;
    const matchMsg = document.getElementById('pwMatchMsg');
    if (!this.value) {
        matchMsg.innerHTML = '';
    } else if (this.value === pw) {
        matchMsg.innerHTML = '<p style="font-size:12px;color:#16a34a;margin-top:4px">✓ Password cocok</p>';
    } else {
        matchMsg.innerHTML = '<p style="font-size:12px;color:#ef4444;margin-top:4px">✗ Password tidak cocok</p>';
    }
});

/* ── Submit ──────────────────────────────────────────── */
function handleSubmit() {
    const name  = document.getElementById('f_name').value.trim();
    const email = document.getElementById('f_email').value.trim();
    const phone = document.getElementById('f_phone').value.trim();
    const pw    = document.getElementById('f_password').value;
    const pwc   = document.getElementById('f_pwconf').value;

    // Validasi
    if (!name) {
        alert('Nama lengkap wajib diisi.');
        document.getElementById('f_name').focus();
        return;
    }
    if (!email) {
        alert('Email wajib diisi.');
        document.getElementById('f_email').focus();
        return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Format email tidak valid.');
        document.getElementById('f_email').focus();
        return;
    }
    if (pw.length < 8) {
        alert('Password minimal 8 karakter.');
        document.getElementById('f_password').focus();
        return;
    }
    if (pw !== pwc) {
        alert('Konfirmasi password tidak cocok.');
        document.getElementById('f_pwconf').focus();
        return;
    }
    if (!document.getElementById('f_terms').checked) {
        alert('Harap setujui syarat dan ketentuan.');
        return;
    }

    setLoading(true);

    fetch('{{ route("register.payment.token") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            name: name, 
            email: email, 
            phone: phone, 
            password: pw, 
            password_confirmation: pwc 
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (!data.snap_token) {
            setLoading(false);
            alert(data.message || 'Gagal memproses pembayaran. Silakan coba lagi.');
            return;
        }
        
        // Open Midtrans popup
        window.snap.pay(data.snap_token, {
            onSuccess: function(result) {
                console.log('Payment Success:', result);
                // Kirim data ke server untuk registrasi final
                submitRegistration(name, email, phone, pw, pwc, result.order_id, result.transaction_status);
            },
            onPending: function(result) {
                console.log('Payment Pending:', result);
                submitRegistration(name, email, phone, pw, pwc, result.order_id, 'pending');
            },
            onError: function(result) {
                console.error('Payment Error:', result);
                setLoading(false);
                alert('Pembayaran gagal: ' + (result.status_message || 'Silakan coba lagi.'));
            },
            onClose: function() {
                console.log('Payment popup closed');
                setLoading(false);
            },
        });
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        setLoading(false);
        alert('Koneksi gagal. Periksa koneksi internet Anda.');
    });
}

function submitRegistration(name, email, phone, pw, pwc, orderId, txStatus) {
    // Isi hidden form
    document.getElementById('hName').value = name;
    document.getElementById('hEmail').value = email;
    document.getElementById('hPhone').value = phone;
    document.getElementById('hPw').value = pw;
    document.getElementById('hPwC').value = pwc;
    document.getElementById('hOrderId').value = orderId;
    document.getElementById('hTxStatus').value = txStatus;
    
    // Submit form
    document.getElementById('finalForm').submit();
}

function setLoading(on) {
    const btn = document.getElementById('btnSubmit');
    const lbl = document.getElementById('btnLabel');
    const ico = document.getElementById('btnIcon');
    
    btn.disabled = on;
    
    if (on) {
        lbl.innerHTML = 'Memproses...';
        ico.innerHTML = '<svg class="w-4 h-4 spin" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>';
    } else {
        lbl.innerHTML = 'Daftar & Bayar';
        ico.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>';
    }
}

/* Auto-hide alerts */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.alert-err, .alert-ok').forEach(el => {
        setTimeout(() => { 
            el.style.transition = 'opacity .5s'; 
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500); 
        }, 6000);
    });
});
</script>
</body>
</html>