<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function monthly()
    {
        $data = DB::table('pembayarans')
            ->select(
                DB::raw('MONTH(tanggal_bayar) as bulan'),
                DB::raw('SUM(total) as total')
            )
            ->whereYear('tanggal_bayar', date('Y'))
            ->groupBy(DB::raw('MONTH(tanggal_bayar)'))
            ->orderBy(DB::raw('MONTH(tanggal_bayar)'))
            ->get();

        $bulan_nama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = $bulan_nama[$item->bulan];
            $values[] = $item->total;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}
