<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\KategoriPembayaran;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // --- Filter tanggal opsional (untuk kartu & chart harian) ---
        $startInput = $request->input('start_date');
        $endInput   = $request->input('end_date');

        $startDate  = $startInput ? Carbon::parse($startInput.' 00:00:00') : Carbon::now()->startOfMonth();
        $endDate    = $endInput   ? Carbon::parse($endInput.' 23:59:59')   : Carbon::now()->endOfMonth();

        // ================================================================
        // 1) Kartu "Total Nominal Lunas (MTD)" + COUNTS
        // ================================================================
        $nominalLunasQuery = DB::table('pembayaran_user as pu')
            ->join('pembayarans as p', 'pu.pembayaran_id', '=', 'p.id')
            ->where('pu.status', 'lunas')
            ->whereBetween('pu.tanggal_pembayaran', [$startDate, $endDate]);

        $nominalLunas = (float) $nominalLunasQuery
            ->sum(DB::raw('COALESCE(pu.jumlah_bayar, p.jumlah)'));

        $nominalLunasCount = (int) (clone $nominalLunasQuery)->count();

        // ================================================================
        // 2) Kartu "Total Outstanding" (disuplai agar Blade lama tidak rusak) + COUNT
        // ================================================================
        $outstandingQuery = DB::table('pembayaran_user as pu')
            ->join('pembayarans as p', 'pu.pembayaran_id', '=', 'p.id')
            ->whereIn(DB::raw('LOWER(pu.status)'), ['belum lunas','belum-lunas','pending']);

        $nominalOutstanding = (float) $outstandingQuery->sum(DB::raw('p.jumlah'));
        $nominalOutstandingCount = (int) (clone $outstandingQuery)->count();

        // ================================================================
        // 3) Kartu "Total Transaksi (MTD)" + COUNT
        // ================================================================
        $totalTransaksiQuery = DB::table('pembayaran_user as pu')
            ->whereBetween('pu.tanggal_pembayaran', [$startDate, $endDate]);

        $totalTransaksi      = (int) $totalTransaksiQuery->count();   // variabel lama (dipakai Blade)
        $totalTransaksiCount = $totalTransaksi;                       // alias jelas

        // ================================================================
        // 4) Data siswa: total + opsi per kelas (+ COUNT alias)
        // ================================================================
        $kelasCounts = Siswa::selectRaw('kelas, COUNT(*) as total')
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->pluck('total', 'kelas'); // ['1'=>5, '2'=>6, ...]

        $totalSiswa       = $kelasCounts->sum();      // variabel lama
        $totalSiswaCount  = $totalSiswa;              // alias count
        $kelasOptions     = $kelasCounts->keys()->values(); // ['1','2','3',...]

        // ================================================================
        // 5) Pengingat jatuh tempo (tidak dipakai, set 0 agar Blade aman)
        // ================================================================
        $transaksiJatuhTempo = 0;

        // ================================================================
        // 6) Grafik 12 bulan (Total Pembayaran per Bulan — YEAR)
        // ================================================================
        $year = now()->year;

        $monthly = DB::table('pembayaran_user as pu')
            ->join('pembayarans as p', 'pu.pembayaran_id', '=', 'p.id')
            ->where('pu.status', 'lunas')
            ->whereYear('pu.tanggal_pembayaran', $year)
            ->selectRaw('MONTH(pu.tanggal_pembayaran) as m, SUM(COALESCE(pu.jumlah_bayar, p.jumlah)) as total')
            ->groupBy('m')
            ->pluck('total', 'm'); // [1=>..., 2=>..., ...]

        $months = [];
        $totals = [];
        $chartMonthsWithTxnCount = 0;

        for ($m = 1; $m <= 12; $m++) {
            $months[] = Carbon::create(null, $m, 1)->locale('id')->translatedFormat('F');
            $val = (float) ($monthly[$m] ?? 0);
            $totals[] = $val;
            if ($val > 0) {
                $chartMonthsWithTxnCount++;
            }
        }

        // ================================================================
        // 7) Chart harian untuk range start_date - end_date (opsional)
        // ================================================================
        $dailyMap = DB::table('pembayaran_user as pu')
            ->join('pembayarans as p', 'pu.pembayaran_id', '=', 'p.id')
            ->where('pu.status', 'lunas')
            ->whereBetween('pu.tanggal_pembayaran', [$startDate, $endDate])
            ->selectRaw('DATE(pu.tanggal_pembayaran) as dt, SUM(COALESCE(pu.jumlah_bayar, p.jumlah)) as total')
            ->groupBy('dt')
            ->pluck('total', 'dt'); // ['2025-10-01'=>..., ...]

        $period = CarbonPeriod::create($startDate->copy()->startOfDay(), $endDate->copy()->startOfDay());
        $chartLabels = [];
        $chartValues = [];
        $chartDaysCount = 0;

        foreach ($period as $date) {
            $c = Carbon::parse($date);
            $key = $c->format('Y-m-d');
            $chartLabels[] = $c->format('d M');
            $chartValues[] = (int) ($dailyMap[$key] ?? 0);
            $chartDaysCount++;
        }

        // ================================================================
        // 8) Recent Usage – 5 transaksi terakhir + TOTAL COUNT & DISTINCT USER COUNT
        // ================================================================
        $recentUsage = DB::table('pembayaran_user as pu')
            ->join('users as u', 'pu.user_id', '=', 'u.id')
            ->join('pembayarans as p', 'pu.pembayaran_id', '=', 'p.id')
            ->where('pu.status', 'lunas')
            ->orderByDesc('pu.tanggal_pembayaran')
            ->limit(5)
            ->get([
                'u.name as nama_siswa',
                'u.kelas',
                'p.nama as nama_pembayaran',
                DB::raw('COALESCE(pu.jumlah_bayar, p.jumlah) as jumlah'),
                'pu.tanggal_pembayaran',
            ]);

        $recentUsageTotalCount = (int) DB::table('pembayaran_user as pu')
            ->where('pu.status', 'lunas')
            ->count();

        $uniqueStudentsPaidCount = (int) DB::table('pembayaran_user as pu')
            ->where('pu.status', 'lunas')
            ->whereBetween('pu.tanggal_pembayaran', [$startDate, $endDate])
            ->distinct('pu.user_id')
            ->count('pu.user_id');

        // ================================================================
        // 9) Kategori pembayaran + COUNT
        // ================================================================
        $kategoriPembayaran = KategoriPembayaran::all();
        $kategoriPembayaranCount = $kategoriPembayaran->count();

        return view('admin.dashboard', compact(
            // lama (dipakai Blade kamu)
            'nominalLunas',
            'nominalOutstanding',
            'totalTransaksi',
            'totalSiswa',
            'kelasCounts',
            'kelasOptions',
            'months',
            'totals',
            'year',
            'transaksiJatuhTempo',
            'kategoriPembayaran',
            // tambahan baru (COUNT & extras)
            'startDate',
            'endDate',
            'chartLabels',
            'chartValues',
            'chartDaysCount',
            'chartMonthsWithTxnCount',
            'recentUsage',
            'recentUsageTotalCount',
            'uniqueStudentsPaidCount',
            'nominalLunasCount',
            'nominalOutstandingCount',
            'totalTransaksiCount',
            'totalSiswaCount',
            'kategoriPembayaranCount'
        ));
    }
}
