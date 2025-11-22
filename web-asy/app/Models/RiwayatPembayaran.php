<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPembayaran extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pembayarans';

    protected $fillable = [
        'pembayaran_id',
        'user_id',
        'status',
        'jumlah_bayar',
        'metode',
        'tanggal_bayar',
        'no_referensi',
        'order_id',
        'payment_type',
        'transaction_status',
        'gross_amount',
        'dibuat_oleh',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'jumlah_bayar'  => 'decimal:2',
    ];

    // Status helper (opsional)
    public const STATUS_LUNAS   = 'lunas';
    public const STATUS_PENDING = 'pending';
    public const STATUS_BATAL   = 'dibatalkan';

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Scope untuk filter umum */
    public function scopeForUser($q, int $userId)
    {
        return $q->where('user_id', $userId);
    }

    public function scopeLunas($q)
    {
        return $q->where('status', self::STATUS_LUNAS);
    }

    public function scopeBetweenDate($q, ?string $start, ?string $end)
    {
        if ($start && $end) {
            $q->whereBetween('tanggal_bayar', ["{$start} 00:00:00", "{$end} 23:59:59"]);
        }
        return $q;
    }
}
