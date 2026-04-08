<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_key')->unique();
            $table->string('kode_lisensi')->unique()->nullable();

            // Durasi & tipe lisensi
            $table->enum('license_type', ['trial', 'basic', 'premium'])->default('basic');
            $table->integer('duration_months')->default(12); // durasi dalam bulan
            $table->date('start_date')->nullable();          // diisi saat approved
            $table->date('expired_date')->nullable();        // diisi saat approved

            $table->boolean('is_active')->default(false);

            // Status pembayaran dari Midtrans
            $table->enum('payment_status', [
                'pending', 'settlement', 'capture',
                'deny', 'expire', 'cancel', 'refund'
            ])->default('pending');

            // Status approval oleh super admin
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable(); // alasan jika ditolak

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};