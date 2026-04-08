<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Approval - AsetKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 transform transition-all">
        <div class="text-center">
            <!-- Success Icon -->
            <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Berhasil! 🎉</h1>
            <p class="text-gray-600 mb-6">
                Terima kasih telah melakukan registrasi dan pembayaran.
            </p>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 text-left rounded">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 font-semibold">Menunggu Approval dari Super Admin</p>
                        <p class="text-sm text-yellow-600 mt-1">
                            Akun Anda akan segera diproses oleh Super Admin. Setelah disetujui, 
                            kami akan mengirimkan email aktivasi ke <strong>{{ $email }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 rounded-lg p-4 mb-6 text-left">
                <p class="text-sm text-blue-800 font-semibold mb-2">📧 Apa yang akan terjadi selanjutnya?</p>
                <ul class="text-sm text-blue-700 space-y-2">
                    <li class="flex items-start">
                        <span class="mr-2">1.</span>
                        <span>Super Admin akan memverifikasi data Anda</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">2.</span>
                        <span>Email aktivasi akan dikirim ke alamat email Anda</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">3.</span>
                        <span>Klik link aktivasi untuk mengaktifkan akun</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">4.</span>
                        <span>Setelah aktif, Anda bisa login ke dashboard</span>
                    </li>
                </ul>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <p class="text-sm text-gray-700 mb-2">📋 Informasi Registrasi:</p>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li><strong>Email:</strong> {{ $email }}</li>
                    <li><strong>Status:</strong> <span class="text-yellow-600 font-semibold">Menunggu Approval</span></li>
                </ul>
            </div>
            
            <a href="{{ route('login') }}" 
               class="w-full inline-block bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition text-center">
                Kembali ke Halaman Login
            </a>
            
            <p class="text-xs text-gray-500 mt-4">
                Jika Anda tidak menerima email dalam waktu 1x24 jam, silakan hubungi customer support.
            </p>
        </div>
    </div>
</body>
</html>