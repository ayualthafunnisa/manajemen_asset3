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
        Schema::create('assets', function (Blueprint $table) {
            $table->id('assetID'); 
            $table->unsignedBigInteger('InstansiID');
            $table->unsignedBigInteger('KategoriID');
            $table->unsignedBigInteger('LokasiID');; 
            
            // Identitas Asset
            $table->string('kode_asset', 50)->unique(); 
            $table->string('nama_asset', 200);
            $table->string('merk', 100)->nullable(); 
            $table->string('tipe_model', 100)->nullable(); 
            $table->string('serial_number', 100)->nullable()->unique(); 
            $table->string('nomor_registrasi', 100)->nullable(); 
            
            // Informasi Perolehan
            $table->enum('sumber_perolehan', ['pembelian', 'hibah', 'bantuan_pemerintah', 'produksi_sendiri', 'sewa'])->default('pembelian'); 
            $table->date('tanggal_perolehan'); 
            $table->date('tanggal_penggunaan')->nullable(); 
            $table->decimal('nilai_perolehan', 15, 2); 
            $table->decimal('nilai_residu', 15, 2)->default(0);
            
            // Informasi Penyusutan
            $table->integer('umur_ekonomis');

            // Status dan Kondisi
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'tidak_berfungsi', 'hilang'])->default('baik'); 
            $table->enum('status_asset', ['aktif', 'non_aktif', 'dipinjam', 'diperbaiki', 'tersimpan', 'dihapus'])->default('aktif'); 
            
            // Spesifikasi Fisik
            $table->string('warna', 50)->nullable(); 
            $table->string('bahan', 100)->nullable(); 
            $table->string('dimensi', 100)->nullable(); 
            $table->string('berat', 50)->nullable();
            $table->integer('jumlah')->default(1); 
            $table->string('satuan', 20)->default('unit');
            
            // Informasi Tambahan
            $table->text('spesifikasi')->nullable();
            $table->string('vendor', 100)->nullable(); 
            $table->string('no_faktur', 100)->nullable(); 
            $table->date('tanggal_faktur')->nullable(); 
            $table->string('gambar_asset', 255)->nullable();
            $table->string('dokumen_pendukung', 255)->nullable(); 
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('InstansiID')->references('InstansiID')->on('instansis');
            $table->foreign('KategoriID')->references('KategoriID')->on('kategori_assets');
            $table->foreign('LokasiID')->references('LokasiID')->on('lokasi_assets');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
