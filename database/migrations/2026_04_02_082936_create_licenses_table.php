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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_key')->unique();
            $table->date('start_date');
            $table->date('expired_date');
            $table->boolean('is_active')->default(true);
            $table->string('kode_lisensi')->unique()->nullable();
            $table->string('snap_token')->nullable(); // Untuk menyimpan token pembayaran
            $table->string('payment_status')->default('pending'); // pending, settlement, expire, cancel
            $table->integer('amount')->default(500000); // Harga lisensi (misal 500rb)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
