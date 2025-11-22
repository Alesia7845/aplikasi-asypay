<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PembayaranExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $query = DB::table('pembayaran_user')
            ->join('users', 'pembayaran_user.user_id', '=', 'users.id')
            ->join('pembayarans', 'pembayaran_user.pembayaran_id', '=', 'pembayarans.id')
            ->where('pembayaran_user.status', 'lunas');

        if ($start && $end) {
            $query->whereBetween('pembayaran_user.tanggal_pembayaran', [
                $start . ' 00:00:00',
                $end . ' 23:59:59',
            ]);
        }

        $laporans = $query->select([
            'users.name as nama_siswa',
            'users.kelas',
            'pembayarans.nama as nama_pembayaran',
            'pembayarans.jumlah',
            'pembayaran_user.tanggal_pembayaran as tanggal_bayar',
            'pembayaran_user.metode',
        ])
        ->orderByDesc('pembayaran_user.tanggal_pembayaran')
        ->get();

        return view('admin.laporan.index', compact('laporans', 'start', 'end'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new PembayaranExport($request->start_date, $request->end_date),
            'laporan_pembayaran.xlsx'
        );
    }

    public function exportPDF(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $query = DB::table('pembayaran_user')
            ->join('users', 'pembayaran_user.user_id', '=', 'users.id')
            ->join('pembayarans', 'pembayaran_user.pembayaran_id', '=', 'pembayarans.id')
            ->where('pembayaran_user.status', 'lunas');

        if ($start && $end) {
            $query->whereBetween('pembayaran_user.tanggal_pembayaran', [
                $start . ' 00:00:00',
                $end . ' 23:59:59',
            ]);
        }

        $laporans = $query->select([
            'users.name as nama_siswa',
            'users.kelas',
            'pembayarans.nama as nama_pembayaran',
            'pembayarans.jumlah',
            'pembayaran_user.tanggal_pembayaran as tanggal_bayar',
            'pembayaran_user.metode',
        ])
        ->orderByDesc('pembayaran_user.tanggal_pembayaran')
        ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('laporans', 'start', 'end'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('laporan_pembayaran.pdf');
    }
}
