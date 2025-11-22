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
        $table->string('status', 255)->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('pembayaran_user', function (Blueprint $table) {
        $table->string('status', 10)->change();  // Menyesuaikan dengan panjang sebelumnya
    });
}
};
