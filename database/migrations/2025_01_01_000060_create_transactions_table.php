<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipe', [
                'topup',        // penambahan saldo dari top up
                'debit',        // pengurangan saldo saat order
                'refund',       // pengembalian saldo saat order dibatalkan
                'income',       // pendapatan penjual dari order
                'withdrawal'    // pencairan saldo penjual
            ]);
            $table->decimal('jumlah', 12, 2);
            $table->decimal('saldo_sebelum', 12, 2);
            $table->decimal('saldo_sesudah', 12, 2);
            $table->string('keterangan');
            $table->nullableMorphs('reference');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
