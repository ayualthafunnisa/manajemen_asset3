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
        Schema::create('perbaikans', function (Blueprint $table) {
            $table->id('perbaikanID');

            // ── Relasi ke kerusakan ───────────────────────────────────────────
            $table->unsignedBigInteger('kerusakanID');
            $table->foreign('kerusakanID')->references('kerusakanID')->on('kerusakans')->onDelete('cascade');

            $table->unsignedBigInteger('InstansiID');
            $table->foreign('InstansiID')->references('InstansiID')->on('instansis')->onDelete('cascade');

            // ── Identifikasi Laporan Perbaikan ────────────────────────────────
            $table->string('kode_perbaikan', 50)->unique();

            // ── Detail Perbaikan ──────────────────────────────────────────────
            $table->text('catatan_perbaikan')->nullable();          // catatan dari teknisi
            $table->text('tindakan_perbaikan')->nullable();         // tindakan yang dilakukan
            $table->string('foto_sesudah')->nullable();             // foto setelah perbaikan
            $table->string('komponen_diganti')->nullable();         // komponen yang diganti
            $table->decimal('biaya_aktual', 15, 2)->nullable();     // biaya riil yang dipakai

            // ── Status Perbaikan ──────────────────────────────────────────────
            $table->enum('status', [
                'menunggu',          // belum ditangani teknisi
                'dalam_perbaikan',   // sedang dikerjakan
                'selesai',           // perbaikan berhasil
                'tidak_bisa_diperbaiki', // tidak dapat diperbaiki
            ])->default('menunggu');

            $table->text('alasan_tidak_bisa')->nullable();          // alasan jika tidak bisa diperbaiki

            // ── Durasi & Timeline ─────────────────────────────────────────────
            $table->timestamp('mulai_perbaikan')->nullable();       // waktu mulai dikerjakan
            $table->timestamp('selesai_perbaikan')->nullable();     // waktu selesai

            // ── Audit Trail ───────────────────────────────────────────────────
            $table->foreignId('teknisi_id')->constrained('users'); // teknisi yang menangani
            $table->foreignId('ditugaskan_oleh')->constrained('users'); // admin yg menugaskan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikans');
    }
};