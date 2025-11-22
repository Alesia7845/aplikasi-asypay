<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Ambil semua pembayaran dari database (bisa disesuaikan untuk siswa login)
            $pembayaran = Pembayaran::with('kategori')
                ->select('id', 'nama', 'nis', 'kelas','jumlah', 'tanggal_tempo')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data pembayaran berhasil diambil dari database!',
                'data' => $pembayaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with('kategori')->find($id);

        if (!$pembayaran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tagihan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $pembayaran
        ]);
    }

    public function bayar(Request $request, $id)
    {
        // nanti disini logika ubah status pembayaran
        return response()->json([
            'status' => 'success',
            'message' => 'Simulasi pembayaran berhasil!'
        ]);
    }
    public function getTagihanAktif($nis)
    {
        try {
            // Ambil data siswa berdasarkan NIS
            $siswa = User::where('nis', $nis)->first();

            if (!$siswa) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Siswa tidak ditemukan',
                ], 404);
            }

            // Ambil semua tagihan yang belum lunas
            $tagihanAktif = $siswa->pembayarans()
                ->wherePivot('status', 'belum lunas')
                ->with('kategori:id,nama')
                ->select('pembayarans.id', 'pembayarans.nama', 'pembayarans.jumlah', 'pembayarans.tanggal_tempo', 'pembayarans.kategori_id')
                ->get();

            return response()->json([
            'status' => 'success',
            'data' => [
                'name' => $siswa->name,        // ✅ tambahkan ini
                'nis' => $siswa->nis,          // ✅ tambahkan ini
                'tagihan_aktif' => $tagihanAktif,
            ]
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function showByNIS($id, $nis)
    {
        $pembayaran = Pembayaran::where('id', $id)->where('nis', $nis)->first();

        if (!$pembayaran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan atau bukan milik siswa ini'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $pembayaran
        ]);
    }

}