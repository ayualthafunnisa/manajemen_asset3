<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom pendukung laporan kerusakan via QR publik ke tabel kerusakans.
     * Jalankan dengan: php artisan migrate
     */
    public function up(): void
    {
        Schema::table('kerusakans', function (Blueprint $table) {

            // Tandai apakah laporan ini berasal dari scan QR publik (tanpa login)
            // atau dibuat manual oleh staf lewat form internal.
            $table->boolean('dari_qr_publik')->default(false)->after('kode_laporan');

            // Nama pelapor eksternal (diisi saat laporan via QR tanpa login).
            // Jika laporan dari staf internal, kolom ini null.
            $table->string('nama_pelapor_publik', 100)->nullable()->after('dari_qr_publik');

            // Kontak pelapor eksternal (HP/email, opsional diisi pelapor).
            $table->string('kontak_pelapor_publik', 100)->nullable()->after('nama_pelapor_publik');

            // Penyebab kerusakan (kolom ini mungkin belum ada di migration awal).
            // Gunakan kondisional agar tidak error jika sudah ada.
            if (!Schema::hasColumn('kerusakans', 'penyebab_kerusakan')) {
                $table->text('penyebab_kerusakan')->nullable()->after('deskripsi_kerusakan');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kerusakans', function (Blueprint $table) {
            $table->dropColumn([
                'dari_qr_publik',
                'nama_pelapor_publik',
                'kontak_pelapor_publik',
            ]);

            // Hanya drop penyebab_kerusakan jika memang ditambahkan di migration ini
            // (jika sudah ada dari awal, jangan di-drop di sini)
        });
    }
};