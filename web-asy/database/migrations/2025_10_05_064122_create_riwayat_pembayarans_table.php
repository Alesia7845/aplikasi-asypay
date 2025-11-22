<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('riwayat_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembayaran_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status')->default('pending');
            $table->decimal('jumlah_bayar', 16, 2)->nullable();
            $table->string('metode')->nullable();
            $table->dateTime('tanggal_bayar')->nullable();
            $table->string('no_referensi')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('dibuat_oleh')->nullable();

            // Kolom Midtrans & transfer
            $table->string('order_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('fraud_status')->nullable();
            $table->dateTime('settlement_time')->nullable();
            $table->decimal('gross_amount', 16, 2)->nullable();
            $table->string('bank_tujuan')->nullable();
            $table->string('no_rek_tujuan')->nullable();
            $table->string('atas_nama')->nullable();
            $table->string('bukti_transfer_path')->nullable();

            $table->timestamps();

            // Index dan relasi
            $table->index('pembayaran_id');
            $table->index('user_id');
            $table->index('order_id');
            $table->foreign('pembayaran_id')->references('id')->on('pembayarans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pembayarans');
    }
};
