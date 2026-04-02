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
        Schema::create('kerusakans', function (Blueprint $table) {
            $table->id('kerusakanID');
 
            // ── Relasi ────────────────────────────────────────────────────────
            $table->unsignedBigInteger('assetID');
            $table->foreign('assetID')->references('assetID')->on('assets')->onDelete('cascade');
 
            $table->unsignedBigInteger('InstansiID');
            $table->foreign('InstansiID')->references('InstansiID')->on('instansis')->onDelete('cascade');
 
            $table->unsignedBigInteger('LokasiID');
            $table->foreign('LokasiID')->references('LokasiID')->on('lokasi_assets')->onDelete('cascade');
 
            // ── Identifikasi Laporan ──────────────────────────────────────────
            $table->string('kode_laporan', 50)->unique();
            $table->date('tanggal_laporan');
            $table->date('tanggal_kerusakan');
 
            // ── Detail Kerusakan ──────────────────────────────────────────────
            $table->enum('jenis_kerusakan', ['ringan', 'sedang', 'berat', 'total']);
            $table->unsignedTinyInteger('tingkat_kerusakan')->default(0); // 25/50/75/100 — diisi otomatis
            $table->string('foto_kerusakan');
            $table->text('deskripsi_kerusakan');
 
            // ── Penanganan ────────────────────────────────────────────────────
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi', 'kritis'])->default('sedang');
            $table->decimal('estimasi_biaya', 15, 2)->nullable();   // nama sesuai controller
 
            // ── Status & Timeline ─────────────────────────────────────────────
            $table->enum('status_perbaikan', ['dilaporkan', 'diproses', 'selesai', 'ditolak'])
                  ->default('dilaporkan');
 
    
            // ── Audit Trail ───────────────────────────────────────────────────
            $table->foreignId('dilaporkan_oleh')->constrained('users');
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerusakans');
    }
};
