<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan kolom pendukung fitur barcode/QR ke tabel assets.
     * Jalankan dengan: php artisan migrate
     */
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {

            // Token unik untuk validasi akses publik laporan kerusakan via QR.
            // Di-generate otomatis saat asset dibuat (lihat model Asset).
            // Contoh: "abc123xyz" → URL: /lapor-kerusakan/abc123xyz
            $table->string('qr_token', 64)->nullable()->unique()->after('kode_asset');

            // Hitung berapa kali QR lapor kerusakan di-scan (opsional, untuk analitik).
            $table->unsignedInteger('qr_scan_count')->default(0)->after('qr_token');

            // Tanggal terakhir QR label dicetak (untuk keperluan audit/histori cetak).
            $table->timestamp('qr_last_printed_at')->nullable()->after('qr_scan_count');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['qr_token', 'qr_scan_count', 'qr_last_printed_at']);
        });
    }
};