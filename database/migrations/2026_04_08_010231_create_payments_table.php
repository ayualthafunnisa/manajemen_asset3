<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('license_id')->constrained()->onDelete('cascade');

            // Data Midtrans
            $table->string('order_id')->unique();       // order_id yang dikirim ke Midtrans
            $table->string('transaction_id')->nullable()->unique(); // dari response Midtrans
            $table->string('snap_token')->nullable();   // snap token untuk payment page

            $table->integer('amount');                  // nominal pembayaran
            $table->string('payment_type')->nullable(); // credit_card, gopay, bca_va, dll
            $table->enum('status', [
                'pending', 'settlement', 'capture',
                'deny', 'expire', 'cancel', 'refund'
            ])->default('pending');

            $table->json('midtrans_response')->nullable(); // simpan raw response Midtrans
            $table->timestamp('paid_at')->nullable();      // waktu pembayaran berhasil

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};