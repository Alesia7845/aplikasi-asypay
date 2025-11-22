<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('pembayaran_user', function (Blueprint $table) {
        $table->timestamp('tanggal_bayar')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('pembayaran_user', function (Blueprint $table) {
        $table->dropColumn('tanggal_bayar');
    });
}
};
