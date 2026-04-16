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
        Schema::table('perbaikans', function (Blueprint $table) {
            $table->date('estimasi_selesai')
                  ->nullable()
                  ->after('mulai_perbaikan')
                  ->comment('Estimasi tanggal selesai perbaikan yang diisi teknisi');
        });
    }
 
    public function down(): void
    {
        Schema::table('perbaikans', function (Blueprint $table) {
            $table->dropColumn('estimasi_selesai');
        });
    }
};
