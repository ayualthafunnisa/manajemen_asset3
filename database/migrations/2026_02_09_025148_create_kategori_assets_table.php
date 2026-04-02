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
        Schema::create('kategori_assets', function (Blueprint $table) {
            $table->id('KategoriID');
            $table->string('KodeKategori');
            $table->string('NamaKategori', 50);
            $table->text('Deskripsi')->nullable();
            $table->unsignedBigInteger('InstansiID');
            $table->timestamps();

            $table->foreign('InstansiID')->references('InstansiID')->on('instansis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_assets');
    }
};
