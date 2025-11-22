<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayarans', 'tanggal_tempo')) {
                $table->date('tanggal_tempo')->nullable()->after('tanggal_buat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            if (Schema::hasColumn('pembayarans', 'tanggal_tempo')) {
                $table->dropColumn('tanggal_tempo');
            }
        });
    }
};
