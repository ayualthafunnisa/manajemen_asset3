<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun - {{ config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.5;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .card {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 14px 0 rgba(124, 58, 237, 0.08);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            padding: 32px 40px;
            text-align: center;
        }
        
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.3px;
            margin: 0;
        }
        
        .header p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            margin-top: 8px;
        }
        
        .content {
            padding: 40px;
        }
        
        .greeting {
            margin-bottom: 24px;
        }
        
        .greeting h2 {
            color: #1e293b;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        
        .greeting p {
            color: #475569;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .info-box {
            background-color: #f8fafc;
            border-left: 3px solid #7c3aed;
            border-radius: 8px;
            padding: 20px;
            margin: 24px 0;
        }
        
        .info-box p {
            color: #475569;
            font-size: 14px;
            margin: 0;
        }
        
        .table-wrapper {
            margin: 24px 0;
            overflow-x: auto;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        
        .info-table td {
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-table td:first-child {
            color: #64748b;
            font-weight: 500;
            width: 40%;
        }
        
        .info-table td:last-child {
            color: #1e293b;
            font-weight: 500;
        }
        
        .info-table tr:last-child td {
            border-bottom: none;
        }
        
        .password-box {
            background-color: #f1f5f9;
            font-family: 'SF Mono', 'Menlo', monospace;
            font-size: 13px;
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-block;
            letter-spacing: 0.5px;
        }
        
        .license-section {
            margin: 32px 0;
        }
        
        .section-title {
            color: #1e293b;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            padding: 12px 28px;
            border-radius: 8px;
            text-align: center;
            margin: 16px 0;
            transition: all 0.2s ease;
        }
        
        .button:hover {
            background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
            transform: translateY(-1px);
        }
        
        .fallback-link {
            background-color: #f8fafc;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 12px;
            word-break: break-all;
            margin: 16px 0;
        }
        
        .fallback-link p {
            color: #64748b;
            margin-bottom: 8px;
        }
        
        .fallback-link a {
            color: #7c3aed;
            text-decoration: none;
        }
        
        .security-note {
            background-color: #fef3c7;
            border-left: 3px solid #f59e0b;
            padding: 16px 20px;
            border-radius: 8px;
            margin: 24px 0;
        }
        
        .security-note p {
            color: #92400e;
            font-size: 13px;
            margin: 0;
        }
        
        .footer {
            padding: 24px 40px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        
        .footer p {
            color: #94a3b8;
            font-size: 12px;
            margin: 0;
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 20px 16px;
            }
            .content {
                padding: 24px;
            }
            .header {
                padding: 24px;
            }
            .footer {
                padding: 20px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>{{ config('app.name') }}</h1>
                <p>Aktivasi Akun</p>
            </div>
            
            <div class="content">
                <div class="greeting">
                    <h2>Halo, {{ $name }}</h2>
                    <p>Akun Anda pada sistem <strong>{{ config('app.name') }}</strong> telah berhasil diaktifkan oleh Administrator. Anda kini dapat mengakses seluruh fitur sesuai dengan paket lisensi yang terdaftar.</p>
                </div>
                
                <div class="info-box">
                    <p>🔒 Untuk keamanan, kami menyarankan Anda untuk segera melakukan perubahan kata sandi setelah berhasil melakukan login pertama kali.</p>
                </div>
                
                <div class="table-wrapper">
                    <table class="info-table">
                        <tr>
                            <td>Nama Pengguna</td>
                            <td>{{ $name }}</td>
                        </tr>
                        <tr>
                            <td>Email Login</td>
                            <td>{{ $email }}</td>
                        </tr>
                        <tr>
                            <td>Kata Sandi Sementara</td>
                            <td><span class="password-box">{{ $password }}</span></td>
                        </tr>
                    </table>
                </div>
                
                <div class="license-section">
                    <div class="section-title">Informasi Lisensi</div>
                    <table class="info-table">
                        <tr>
                            <td>ID Lisensi</td>
                            <td>{{ $licenseKey }}</td>
                        </tr>
                        <tr>
                            <td>Tipe Paket</td>
                            <td>{{ ucfirst($licenseType) }}</td>
                        </tr>
                        <tr>
                            <td>Masa Berlaku</td>
                            <td>{{ $expiredDate }}</td>
                        </tr>
                    </table>
                </div>
                
                <div style="text-align: center;">
                    <a href="{{ $loginUrl }}" class="button">Login ke Dashboard</a>
                </div>
                
                <div class="fallback-link">
                    <p>Jika tombol di atas tidak berfungsi, salin tautan berikut ke browser Anda:</p>
                    <a href="{{ $loginUrl }}">{{ $loginUrl }}</a>
                </div>
                
                <div class="security-note">
                    <p>⚠️ Jangan memberikan kredensial login Anda kepada pihak manapun. Jika Anda tidak merasa melakukan pendaftaran ini, harap hubungi tim dukungan kami segera.</p>
                </div>
            </div>
            
            <div class="footer">
                <p>Hormat kami, <strong>Tim Administrasi {{ config('app.name') }}</strong></p>
            </div>
        </div>
    </div>
</body>
</html>