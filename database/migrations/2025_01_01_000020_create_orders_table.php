<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_order')->unique()->comment('Format: ORD-YYYYMMDD-XXXX');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', [
                'pending',      // baru dipesan, belum dibayar
                'paid',         // saldo telah dipotong
                'preparing',    // sedang disiapkan penjual
                'ready',        // siap diambil
                'completed',    // sudah diambil
                'cancelled'     // dibatalkan
            ])->default('pending');
            $table->enum('waktu_pengambilan', ['istirahat_1', 'istirahat_2']);
            $table->text('catatan')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
