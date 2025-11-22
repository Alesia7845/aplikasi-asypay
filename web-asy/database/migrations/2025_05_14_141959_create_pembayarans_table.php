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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')
                ->constrained('kategori_pembayarans')
                ->onDelete('cascade');
            $table->string('nama');
            $table->string('nis');
            $table->string('kelas')->nullable();
            $table->decimal('jumlah', 10, 2); // kolom jumlah untuk menyimpan pembayaran
            $table->date('tanggal_buat');
            $table->date('tanggal_tempo')->nullable();
            $table->string('foto')->nullable(); // Foto bukti pembayaran (jika ada)
            $table->enum('status', ['lunas', 'belum lunas'])->default('belum lunas');
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
