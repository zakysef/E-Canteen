<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->decimal('jumlah', 12, 2);
            $table->string('metode_pembayaran')->comment('BCA, BRI, GoPay, OVO, dll');
            $table->string('nomor_rekening');
            $table->string('atas_nama');
            $table->enum('status', ['pending', 'approved', 'rejected', 'transferred'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_admin')->nullable();
            $table->string('bukti_transfer')->nullable()->comment('Bukti transfer dari super admin');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('transferred_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};
