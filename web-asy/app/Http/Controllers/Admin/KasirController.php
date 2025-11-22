<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    /**
     * 1) Menampilkan daftar pembayaran untuk Kasir.
     */
    public function index()
    {
        $pembayarans = DB::table('pembayaran_user')
            ->join('pembayarans', 'pembayaran_user.pembayaran_id', '=', 'pembayarans.id')
            ->join('users', 'pembayaran_user.user_id', '=', 'users.id')
            ->whereIn('pembayaran_user.status', ['belum-lunas', 'menunggu-verifikasi', 'lunas'])
            ->select(
                'pembayaran_user.*',
                'pembayarans.jumlah',
                'pembayarans.tanggal_tempo',
                'pembayarans.nama as nama_pembayaran',
                'users.name as nama_siswa',
                'users.kelas',
                'pembayaran_user.id as pivot_id'
            )
            ->orderBy('pembayarans.tanggal_tempo')
            ->paginate(10);

        return view('admin.kasir.index', compact('pembayarans'));
    }

    /**
     * 2) Menampilkan form verifikasi pembayaran tunai
     *    (hanya jika status = menunggu-verifikasi).
     */
    public function bayarForm($pivotId)
    {
        $pembayaran = DB::table('pembayaran_user')
            ->join('pembayarans', 'pembayaran_user.pembayaran_id', '=', 'pembayarans.id')
            ->join('users', 'pembayaran_user.user_id', '=', 'users.id')
            ->where('pembayaran_user.id', $pivotId)
            ->select(
                'pembayaran_user.*',
                'pembayarans.jumlah',
                'pembayarans.tanggal_tempo',
                'pembayarans.nama as nama_pembayaran',
                'pembayarans.keterangan',
                'users.name as nama_siswa',
                'users.kelas',
                'pembayaran_user.id as pivot_id'
            )
            ->first();

        if (!$pembayaran || $pembayaran->status !== 'menunggu-verifikasi') {
            return redirect()->route('admin.kasir.index')
                             ->with('error', 'Pembayaran sudah diverifikasi atau tidak dapat diproses.');
        }

        return view('admin.kasir.bayarForm', compact('pembayaran'));
    }

    /**
     * 3) Memproses form verifikasi (update status → 'lunas', tanggal_pembayaran).
     */
    public function prosesBayar(Request $request, $pivotId)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
        ]);

        $pembayaranData = DB::table('pembayaran_user')
            ->join('pembayarans', 'pembayaran_user.pembayaran_id', '=', 'pembayarans.id')
            ->where('pembayaran_user.id', $pivotId)
            ->select('pembayaran_user.*', 'pembayarans.jumlah')
            ->first();

        if (!$pembayaranData) {
            return redirect()->route('admin.kasir.index')
                             ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($request->jumlah_bayar < $pembayaranData->jumlah) {
            return back()->withErrors(['jumlah_bayar' => 'Jumlah bayar tidak boleh kurang dari total tagihan.']);
        }

        // Hanya update status dan tanggal_pembayaran — kolom lain tidak ada.
        DB::table('pembayaran_user')
            ->where('id', $pivotId)
            ->update([
                'status'             => 'lunas',
                'tanggal_pembayaran' => now(),
            ]);

        return redirect()->route('admin.kasir.index')
                         ->with('success', 'Pembayaran berhasil diverifikasi dan diproses.');
    }

    /**
     * 4) (Opsional) Proses siswa klik “Bayar Tunai” dari sisi siswa/frontend.
     *    Update status → 'menunggu-verifikasi' dan tanggal_pembayaran (opsional),
     *    kemudian redirect ke form kasir.
     */
    public function prosesPaymentTunai($pivotId)
    {
        $pivot = DB::table('pembayaran_user')->where('id', $pivotId)->first();

        if (!$pivot || $pivot->status != 'belum-lunas') {
            return redirect()->back()->with('error', 'Tagihan tidak valid atau sudah dibayar.');
        }

        DB::table('pembayaran_user')
            ->where('id', $pivotId)
            ->update([
                'status'             => 'menunggu-verifikasi',
                'tanggal_pembayaran' => now(), // catat waktu siswa melakukan request bayar tunai
            ]);

        return redirect()->route('admin.kasir.bayarForm', $pivotId)
                         ->with('success', 'Silakan lakukan pembayaran tunai di kasir.');
    }
}
