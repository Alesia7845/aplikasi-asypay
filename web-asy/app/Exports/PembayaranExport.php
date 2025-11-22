<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PembayaranExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Pembayaran::with('kategori');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_buat', [$this->startDate, $this->endDate]);
        }

        return $query->get()->map(function ($item) {
            return [
                'Nama' => $item->nama,
                'Kelas' => $item->kelas,
                'Kategori' => $item->kategori->nama ?? '',
                'Total' => $item->total,
                'Tanggal Buat' => $item->tanggal_buat,
                'Tanggal Tempo' => $item->tanggal_tempo,
                'Status' => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama', 'Kelas', 'Kategori', 'Total', 'Tanggal Buat', 'Tanggal Tempo', 'Status'];
    }
}
