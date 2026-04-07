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
            $table->date('start_date');
            $table->date('expired_date');
            $table->boolean('is_active')->default(false); // FALSE sampai disetujui Super Admin
            $table->string('kode_lisensi')->unique()->nullable();
            $table->string('snap_token')->nullable();
            $table->string('payment_status')->default('pending'); // pending, settlement, expire, cancel
            $table->string('approval_status')->default('pending'); // pending, approved, rejected
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->integer('amount')->default(500000);
            $table->timestamps();
            
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};