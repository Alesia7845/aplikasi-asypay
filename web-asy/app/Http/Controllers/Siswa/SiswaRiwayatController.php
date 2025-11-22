<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaRiwayatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $pembayaran = $request->pembayaran;
        $start = $request->start_date;
        $end   = $request->end_date;

        $q = DB::table('pembayaran_user as pu')
            ->join('users as u', 'pu.user_id', '=', 'u.id')
            ->join('pembayarans as p', 'pu.pembayaran_id', '=', 'p.id')
            ->where('pu.user_id', $user->id)
            ->whereIn(DB::raw('LOWER(pu.status)'), ['lunas'])          // cover tunai & transfer
            ->orderByDesc('pu.tanggal_pembayaran')
            ->select([
                'pu.id as id',
                'p.nama as nama_pembayaran',
                DB::raw('COALESCE(p.total, p.jumlah) as total_tagihan'),
                DB::raw('COALESCE(pu.jumlah_bayar, COALESCE(p.total, p.jumlah)) as jumlah_bayar'),
                'pu.tanggal_pembayaran as tanggal_bayar',
                DB::raw("COALESCE(NULLIF(pu.metode,''), '-') as metode"),
                DB::raw("LOWER(pu.status) as status"),
            ]);

        // === TAMBAHAN: field fallback (tidak menghilangkan select di atas)
        $q->addSelect([
            DB::raw('pu.pembayaran_id as _pembayaran_id'),
            DB::raw('(SELECT jumlah FROM pembayarans WHERE pembayarans.id = pu.pembayaran_id) as total_tagihan_fix'),
            DB::raw('(SELECT jumlah_bayar FROM pembayaran_user WHERE id = pu.id) as jumlah_bayar_fix'),
        ]);

        if ($pembayaran) $q->where('p.nama', 'like', "%{$pembayaran}%");
        if ($start && $end) $q->whereBetween('pu.tanggal_pembayaran', ["{$start} 00:00:00","{$end} 23:59:59"]);

        $riwayat = $q->get();

        // === TAMBAHAN: normalisasi agar tidak 0/null di tampilan
        foreach ($riwayat as $row) {
            if (empty($row->total_tagihan) || (is_numeric($row->total_tagihan) && (int)$row->total_tagihan === 0)) {
                $row->total_tagihan = (int) ($row->total_tagihan_fix ?? 0);
            }
            if (empty($row->jumlah_bayar) || (is_numeric($row->jumlah_bayar) && (int)$row->jumlah_bayar === 0)) {
                $row->jumlah_bayar = (int) ($row->jumlah_bayar_fix ?? $row->total_tagihan ?? 0);
            }
            // opsional bersihkan properti bantu:
            // unset($row->_pembayaran_id, $row->total_tagihan_fix, $row->jumlah_bayar_fix);
        }

        return view('siswa.riwayat.index', compact('riwayat'));
    }

    public function cetak($id)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $data = DB::table('pembayaran_user as pu')
            ->join('pembayarans as p', 'pu.pembayaran_id', '=', 'p.id')
            ->join('users as u', 'pu.user_id', '=', 'u.id')
            ->where('pu.user_id', $user->id)
            ->where('pu.id', $id)
            ->whereIn(DB::raw('LOWER(pu.status)'), ['lunas'])
            ->first([
                'u.name as nama_siswa',
                'u.kelas',
                'p.nama as nama_pembayaran',
                DB::raw('COALESCE(p.total, p.jumlah) as total_tagihan'),
                DB::raw('COALESCE(pu.jumlah_bayar, COALESCE(p.total, p.jumlah)) as jumlah_bayar'),
                'pu.tanggal_pembayaran as tanggal_bayar',
                DB::raw("COALESCE(NULLIF(pu.metode,''), '-') as metode"),
                DB::raw("LOWER(pu.status) as status"),
                // === TAMBAHAN fallback
                DB::raw('p.jumlah as total_tagihan_fix'),
                DB::raw('pu.jumlah_bayar as jumlah_bayar_fix'),
            ]);

        if (!$data) return redirect()->route('riwayat.index')->with('error','Struk tidak ditemukan.');

        // === TAMBAHAN: normalisasi angka
        if (empty($data->total_tagihan) || (is_numeric($data->total_tagihan) && (int)$data->total_tagihan === 0)) {
            $data->total_tagihan = (int) ($data->total_tagihan_fix ?? 0);
        }
        if (empty($data->jumlah_bayar) || (is_numeric($data->jumlah_bayar) && (int)$data->jumlah_bayar === 0)) {
            $data->jumlah_bayar = (int) ($data->jumlah_bayar_fix ?? $data->total_tagihan ?? 0);
        }

        return view('siswa.riwayat.struk', compact('data'));
    }

    public function cetakPDF($id)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $data = DB::table('pembayaran_user as pu')
            ->join('pembayarans as p', 'pu.pembayaran_id', '=', 'p.id')
            ->join('users as u', 'pu.user_id', '=', 'u.id')
            ->where('pu.user_id', $user->id)
            ->where('pu.id', $id)
            ->whereIn(DB::raw('LOWER(pu.status)'), ['lunas'])
            ->first([
                'u.name as nama_siswa',
                'u.kelas',
                'p.nama as nama_pembayaran',
                DB::raw('COALESCE(p.total, p.jumlah) as total_tagihan'),
                DB::raw('COALESCE(pu.jumlah_bayar, COALESCE(p.total, p.jumlah)) as jumlah_bayar'),
                'pu.tanggal_pembayaran as tanggal_bayar',
                DB::raw("COALESCE(NULLIF(pu.metode,''), '-') as metode"),
                DB::raw("LOWER(pu.status) as status"),
                // === TAMBAHAN fallback
                DB::raw('p.jumlah as total_tagihan_fix'),
                DB::raw('pu.jumlah_bayar as jumlah_bayar_fix'),
            ]);

        if (!$data) return redirect()->route('riwayat.index')->with('error','Struk tidak ditemukan.');

        // === TAMBAHAN: normalisasi angka
        if (empty($data->total_tagihan) || (is_numeric($data->total_tagihan) && (int)$data->total_tagihan === 0)) {
            $data->total_tagihan = (int) ($data->total_tagihan_fix ?? 0);
        }
        if (empty($data->jumlah_bayar) || (is_numeric($data->jumlah_bayar) && (int)$data->jumlah_bayar === 0)) {
            $data->jumlah_bayar = (int) ($data->jumlah_bayar_fix ?? $data->total_tagihan ?? 0);
        }

        $pdf = Pdf::loadView('siswa.riwayat.struk_pdf', compact('data'))->setPaper('A4','portrait');
        $nama = preg_replace('/[^a-z0-9_\-]/i','_', strtolower($data->nama_pembayaran));
        return $pdf->download('struk_'.$nama.'_'.date('Ymd_His', strtotime($data->tanggal_bayar ?? now())).'.pdf');
    }
}
