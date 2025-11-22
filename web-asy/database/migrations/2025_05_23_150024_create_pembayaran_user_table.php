<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayaran_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pembayaran_id')->constrained('pembayarans')->onDelete('cascade');
            $table->string('nis');
            $table->enum('status', ['belum-lunas', 'menunggu-verifikasi', 'lunas', 'dibatalkan'])->default('belum-lunas');
            $table->string('order_id')->nullable(); // Order ID dari Midtrans
            $table->timestamp('tanggal_pembayaran')->nullable();
            $table->string('metode')->nullable(); // tunai / transfer
            $table->string('bukti_transfer')->nullable();

            // Kolom untuk data callback Midtrans
            $table->string('payment_type')->nullable();
            $table->string('transaction_status')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'pembayaran_id']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_user');
    }
};
