<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom jumlah_bayar ke pembayaran_user tanpa after('jumlah') karena kolom jumlah tidak ada.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('pembayaran_user', 'jumlah_bayar')) {
            Schema::table('pembayaran_user', function (Blueprint $table) {
                $table->integer('jumlah_bayar')->nullable();
            });
        }
    }

    /**
     * Hapus kolom jumlah_bayar jika rollback.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pembayaran_user', 'jumlah_bayar')) {
            Schema::table('pembayaran_user', function (Blueprint $table) {
                $table->dropColumn('jumlah_bayar');
            });
        }
    }
};
