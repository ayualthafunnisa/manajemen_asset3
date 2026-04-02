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
        Schema::create('instansis', function (Blueprint $table) {
            $table->id('InstansiID');
            $table->string('KodeInstansi', 20)->unique();
            $table->string('NPSN')->unique();
            $table->string('NamaSekolah');
            $table->enum('JenjangSekolah', ['SD', 'SMP', 'SMA', 'SMK']);
            $table->string('provinsi_code', 10)->nullable();
            $table->string('kota_code', 10)->nullable();
            $table->string('kecamatan_code', 10)->nullable();
            $table->string('kelurahan_code', 10)->nullable();
            $table->string('KodePos');
            $table->string('EmailSekolah')->unique();
            $table->string('Logo');
            $table->string('NamaKepalaSekolah');
            $table->string('NIPKepalaSekolah');
            $table->date('TanggalBerdiri');
            $table->enum('Status', ['Aktif', 'Inactive']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instansis');
    }
};
