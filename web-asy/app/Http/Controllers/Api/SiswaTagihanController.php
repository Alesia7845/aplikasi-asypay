<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class SiswaTagihanController extends Controller
{
    public function summary($nis)
    {
        // Cari siswa di tabel USERS, bukan tabel SISWA
        $siswa = User::where('nis', $nis)->first();

        if (!$siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        // Ambil semua tagihan via relasi
        $tagihan = $siswa->pembayarans()->with('kategori')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'nama' => $siswa->name,
                'nis' => $siswa->nis,
                'tagihan_aktif' => $tagihan->where('pivot.status', 'belum lunas')->count(),
                'tunggakan' => $tagihan->where('pivot.status', 'belum lunas')->sum('jumlah'),
                'daftar_tagihan' => $tagihan
            ]
        ]);
    }

    public function getTunggakan($nis)
    {
        try {
            $today = now()->toDateString();

            $tunggakan = Tagihan::where('nis', $nis)
                ->where('status', 'belum lunas')
                ->whereDate('tanggal_tempo', '<', $today)
                ->with('kategori')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data tunggakan ditemukan',
                'data' => $tunggakan
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
