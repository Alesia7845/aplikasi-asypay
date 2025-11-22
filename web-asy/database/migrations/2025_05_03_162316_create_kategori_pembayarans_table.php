<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('kategori_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['Harian', 'Bulanan', 'Tahunan', 'Bebas']);
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('kategori_pembayarans');
    }
};
