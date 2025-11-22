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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nis')->unique()->nullable(); // NIS hanya untuk siswa
            $table->string('kelas')->nullable(); // Kelas hanya untuk siswa
            $table->string('tahun_ajaran')->nullable(); // Tambahan: Tahun ajaran
            $table->string('role')->default('siswa'); // Default 'siswa', admin bisa diubah nanti
            $table->string('email')->nullable()->unique(); // Email bisa kosong untuk siswa
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
