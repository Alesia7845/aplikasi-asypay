<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJumlahToPembayaransTable extends Migration
{
    /**
     * Run the migrations: tambahkan kolom `jumlah` ke tabel `pembayarans`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Pastikan kolom 'jumlah' belum ada agar migrasi tidak gagal jika sudah ada
            if (! Schema::hasColumn('pembayarans', 'jumlah')) {
                // Tambahkan kolom 'jumlah' dengan tipe FLOAT dan default 0
                // Letakkan setelah kolom 'kelas'; sesuaikan urutan jika perlu
                $table->float('jumlah')->default(0)->after('kelas');
            }
        });
    }

    /**
     * Reverse the migrations: hapus kolom `jumlah` jika ada.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Hanya drop kolom jika benar-benar ada
            if (Schema::hasColumn('pembayarans', 'jumlah')) {
                $table->dropColumn('jumlah');
            }
        });
    }
}
