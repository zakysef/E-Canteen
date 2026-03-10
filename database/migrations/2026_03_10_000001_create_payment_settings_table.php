<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. 'bank_bni', 'gopay', 'dana', 'ovo'
            $table->string('type');          // 'bank' or 'ewallet'
            $table->string('label');         // Display name, e.g. "Bank BNI", "GoPay"
            $table->string('account_name')->nullable();   // Nama pemilik rekening
            $table->string('account_number')->nullable(); // Nomor rekening / HP
            $table->string('qr_code')->nullable();        // path to QR image (storage)
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
