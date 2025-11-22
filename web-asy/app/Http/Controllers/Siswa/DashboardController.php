<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Ambil 5 pembayaran terakhir dengan status 'lunas' dari tabel pivot 'pembayaran_user'
        $siswaBayar = DB::table('pembayaran_user')
            ->join('pembayarans', 'pembayaran_user.pembayaran_id', '=', 'pembayarans.id')
            ->where('pembayaran_user.user_id', $userId)
            ->where('pembayaran_user.status', 'lunas')
            ->select(
                'pembayarans.nama as nama_pembayaran',
                'pembayarans.jumlah as total_tagihan',
                'pembayaran_user.tanggal_pembayaran' // hanya ini karena 'jumlah_bayar' tidak ada
            )
            ->orderByDesc('pembayaran_user.tanggal_pembayaran')
            ->limit(5)
            ->get();

        return view('siswa.dashboard', compact('siswaBayar'));
    }
}
