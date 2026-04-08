<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // target penerima

            $table->string('type');    // misal: payment_pending_approval, license_approved, dll
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // data tambahan (license_id, payment_id, dll)

            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'is_read']); // index untuk query notif belum dibaca
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};