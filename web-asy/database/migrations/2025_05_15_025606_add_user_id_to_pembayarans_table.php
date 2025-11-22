<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToPembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Pastikan kolom user_id belum ada
            if (!Schema::hasColumn('pembayarans', 'user_id')) {
                // Tambahkan kolom user_id (nullable) dan foreign key ke tabel users
                $table->foreignId('user_id')
                      ->nullable()
                      ->constrained('users')
                      ->onDelete('set null')
                      ->after('id'); // atau letakkan setelah kolom yang diinginkan
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Hanya drop jika kolom user_id benar-benar ada
            if (Schema::hasColumn('pembayarans', 'user_id')) {
                // Drop foreign key constraint terlebih dahulu
                $table->dropForeign(['user_id']);
                // Lalu drop kolom user_id
                $table->dropColumn('user_id');
            }
        });
    }
}
