<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update enum status agar bisa menampung 'menunggu-verifikasi'
        DB::statement("ALTER TABLE pembayarans MODIFY COLUMN status ENUM('lunas', 'belum lunas', 'menunggu-verifikasi') NOT NULL DEFAULT 'belum lunas'");

        // Tambah kolom tanggal_bayar jika belum ada
        if (!Schema::hasColumn('pembayarans', 'tanggal_bayar')) {
            Schema::table('pembayarans', function (Blueprint $table) {
                $table->timestamp('tanggal_bayar')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        // Kembalikan enum status ke hanya 'lunas' dan 'belum lunas'
        DB::statement("ALTER TABLE pembayarans MODIFY COLUMN status ENUM('lunas', 'belum lunas') NOT NULL DEFAULT 'belum lunas'");

        // Hapus kolom tanggal_bayar
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn('tanggal_bayar');
        });
    }
};
