<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\KategoriPembayaran;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'kategori_id', // BUKAN kategori_pembayaran_id
        'nama',
        'kelas',
        'nis',
        'jumlah',
        'tanggal_buat',
        'tanggal_tempo',
        'status',
    ];

    protected $casts = [
        'tanggal_buat' => 'date',
        'tanggal_tempo' => 'date',
        'jumlah' => 'float',
    ];

    /**
     * Relasi many-to-many ke siswa (user) menggunakan pivot table pembayaran_user
     */
    public function siswa()
    {
        return $this->belongsToMany(User::class, 'pembayaran_user')
            ->withPivot('status', 'tanggal_pembayaran', 'metode', 'order_id', 'bukti_transfer')
            ->withTimestamps();
    }
    public function kategori()
    {
        return $this->belongsTo(KategoriPembayaran::class, 'kategori_id');
    }

    public function siswa_by_nis()
    {
        return $this->belongsTo(User::class, 'nis', 'nis');
    }
}
