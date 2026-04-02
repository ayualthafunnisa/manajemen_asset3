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
        Schema::create('lokasi_assets', function (Blueprint $table) {
            $table->id('LokasiID');
            $table->unsignedBigInteger('InstansiID');
            $table->string('KodeLokasi', 20);
            $table->string('NamaLokasi', 100);
            $table->string('Gedung', 100)->nullable();
            $table->string('Lantai', 10)->nullable();
            $table->string('Ruangan', 50)->nullable();
            $table->string('PenanggungJawab', 100)->nullable();
            $table->string('TeleponPenanggungJawab', 20)->nullable(); 
            $table->decimal('LuasRuangan', 10, 2)->nullable(); 
            $table->enum('JenisLokasi', ['ruangan', 'gudang', 'laboratorium', 'lapangan', 'lainnya']);
            $table->enum('Status', ['active', 'maintenance', 'tidak_aktif', 'renovasi'])->default('active'); 
            $table->text('Keterangan')->nullable(); 
            $table->timestamps();

            $table->foreign('InstansiID')->references('InstansiID')->on('instansis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_assets');
    }
};
