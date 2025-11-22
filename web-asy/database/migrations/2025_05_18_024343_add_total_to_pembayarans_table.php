<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalToPembayaransTable extends Migration
{
    /**
     * Run the migrations: Tambahkan kolom 'total' jika belum ada.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayarans', 'total')) {
                $table->float('total')->default(0)->after('jumlah');
            }
        });
    }

    /**
     * Reverse the migrations: Hapus kolom 'total' jika ada.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            if (Schema::hasColumn('pembayarans', 'total')) {
                $table->dropColumn('total');
            }
        });
    }
}
