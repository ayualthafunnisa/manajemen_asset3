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
        Schema::create('penyusutans', function (Blueprint $table) {
            $table->id('penyusutanID');

            // Relasi ke Asset
            $table->unsignedBigInteger('assetID');
            $table->foreign('assetID')->references('assetID')->on('assets') ->onDelete('cascade');

            // Relasi ke Instansi
            $table->unsignedBigInteger('InstansiID');
            $table->foreign('InstansiID')->references('InstansiID')->on('instansis')->onDelete('cascade');

            // Periode
            $table->integer('tahun');
            $table->integer('bulan')->nullable();

            // Nilai
            $table->decimal('nilai_awal', 15, 2);
            $table->decimal('nilai_penyusutan', 15, 2);
            $table->decimal('nilai_akhir', 15, 2);
            $table->decimal('akumulasi_penyusutan', 15, 2)->default(0);

            // Metode
            $table->enum('metode', ['garis_lurus', 'saldo_menurun']);
            $table->decimal('persentase_penyusutan', 5, 2)->nullable();

            $table->text('catatan')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyusutans');
    }
};
