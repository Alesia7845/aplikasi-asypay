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
        Schema::table('pembayaran_user', function (Blueprint $table) {
            // Cek apakah kolom 'tanggal_verifikasi' sudah ada
            if (!Schema::hasColumn('pembayaran_user', 'tanggal_verifikasi')) {
                $table->timestamp('tanggal_verifikasi')->nullable()->after('tanggal_pembayaran');
            }

            // Cek apakah kolom 'jumlah_bayar' sudah ada
            if (!Schema::hasColumn('pembayaran_user', 'jumlah_bayar')) {
                $table->decimal('jumlah_bayar', 15, 2)->nullable()->after('tanggal_verifikasi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_user', function (Blueprint $table) {
            $table->dropColumn(['tanggal_verifikasi', 'jumlah_bayar']);
        });
    }
};
