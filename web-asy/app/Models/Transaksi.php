<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['waktu', 'total_bayar', 'uang_dibayar', 'kembalian'];

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
