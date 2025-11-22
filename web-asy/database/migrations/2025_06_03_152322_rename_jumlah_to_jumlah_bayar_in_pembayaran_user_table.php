<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('pembayaran_user', 'jumlah')) {
            Schema::table('pembayaran_user', function (Blueprint $table) {
                $table->renameColumn('jumlah', 'jumlah_bayar');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pembayaran_user', 'jumlah_bayar')) {
            Schema::table('pembayaran_user', function (Blueprint $table) {
                $table->renameColumn('jumlah_bayar', 'jumlah');
            });
        }
    }
};
