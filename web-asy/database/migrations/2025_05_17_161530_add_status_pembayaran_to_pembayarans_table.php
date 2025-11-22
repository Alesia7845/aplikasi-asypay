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
    Schema::table('pembayarans', function (Blueprint $table) {
        $table->string('status_pembayaran')->default('Pending'); // contoh default value
    });
}

public function down()
{
    Schema::table('pembayarans', function (Blueprint $table) {
        $table->dropColumn('status_pembayaran');
    });
}

};
