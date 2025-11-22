@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Laporan Pembayaran Siswa (Sudah Lunas)</h1>

    <form action="{{ route('admin.laporan.index') }}" method="GET" class="mb-6 flex flex-wrap items-end gap-4">
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $start) }}"
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $end) }}"
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Filter</button>
        </div>
        <div class="mt-6 flex gap-2">
            <a href="{{ route('admin.laporan.export.excel', ['start_date' => $start, 'end_date' => $end]) }}"
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">Export ke Excel</a>
            <a href="{{ route('admin.laporan.export.pdf', ['start_date' => $start, 'end_date' => $end]) }}"
               class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">Export ke PDF</a>
        </div>
    </form>

    @if($laporans->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            <p>Tidak ada pembayaran yang sudah lunas dalam rentang tanggal ini.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b text-left">No</th>
                        <th class="py-2 px-4 border-b text-left">Nama Siswa</th>
                        <th class="py-2 px-4 border-b text-left">Kelas</th>
                        <th class="py-2 px-4 border-b text-left">Nama Pembayaran</th>
                        <th class="py-2 px-4 border-b text-right">Jumlah (Rp)</th>
                        <th class="py-2 px-4 border-b text-left">Tanggal Bayar</th>
                        <th class="py-2 px-4 border-b text-left">Metode</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporans as $index => $item)
                        <tr class="@if($loop->even) bg-gray-50 @endif">
                            <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                            <td class="py-2 px-4 border-b">{{ $item->nama_siswa }}</td>
                            <td class="py-2 px-4 border-b">{{ $item->kelas }}</td>
                            <td class="py-2 px-4 border-b">{{ $item->nama_pembayaran }}</td>
                            <td class="py-2 px-4 border-b text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d-m-Y H:i') }}</td>
                            <td class="py-2 px-4 border-b capitalize">{{ $item->metode }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
