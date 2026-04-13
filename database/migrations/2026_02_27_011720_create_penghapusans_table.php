<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penghapusans', function (Blueprint $table) {
            $table->id('penghapusanID');
            // Relasi ke Asset
            $table->unsignedBigInteger('assetID');
            $table->foreign('assetID')->references('assetID')->on('assets') ->onDelete('cascade');

            // Relasi ke Instansi
            $table->unsignedBigInteger('InstansiID');
            $table->foreign('InstansiID')->references('InstansiID')->on('instansis')->onDelete('cascade');
            
            // Identifikasi Penghapusan
            $table->string('no_surat_penghapusan', 50)->unique(); // Nomor surat keputusan penghapusan
            $table->date('tanggal_pengajuan'); // Tanggal pengajuan penghapusan
            $table->date('tanggal_penghapusan')->nullable(); // Tanggal disetujui/dieksekusi
            
            // Alasan dan Jenis
            $table->enum('alasan_penghapusan', [
                'kerusakan_permanen', 
                'teknologi_tertinggal',
                'tidak_layak_pakai',
                'kehilangan',
                'penggantian',
                'restrukturisasi'
            ]); // Alasan spesifik penghapusan
            
            // Nilai dan Keuangan
            $table->decimal('nilai_buku', 15, 2); // Nilai buku saat penghapusan
            $table->decimal('harga_jual', 15, 2)->nullable(); // Harga jual jika dijual
            $table->decimal('kerugian_keuntungan', 15, 2)->nullable(); // Selisih nilai buku dengan harga jual
            
            // Dokumen dan Keterangan
            $table->text('deskripsi_penghapusan'); // Penjelasan detail
            $table->string('dokumen_pendukung', 255)->nullable(); // Path file surat/dokumen
            
            // Proses Approval
            $table->foreignId('diajukan_oleh')->constrained('users'); // User yang mengajukan
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users'); // User yang menyetujui
            $table->date('tanggal_persetujuan')->nullable(); // Tanggal persetujuan
            
            // Status
            $table->enum('status_penghapusan', [
                'diajukan',
                'disetujui', 
                'ditolak',
                'selesai'
            ])->default('diajukan');
            
            $table->text('alasan_penolakan')->nullable(); // Alasan jika ditolak
            $table->text('keterangan')->nullable(); // Catatan tambahan
            $table->timestamps(); // created_at dan updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penghapusans');
    }
};
