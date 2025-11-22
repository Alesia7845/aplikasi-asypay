@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Dashboard Siswa</h1>

    <h2 class="text-xl font-semibold mb-2">5 Pembayaran Terakhir (Lunas)</h2>

    @if($siswaBayar->isEmpty())
        <p class="text-gray-600">Belum ada pembayaran yang lunas.</p>
    @else
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left">Nama Pembayaran</th>
                    <th class="py-2 px-4 border-b text-left">Total Tagihan</th>
                    <th class="py-2 px-4 border-b text-left">Tanggal Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($siswaBayar as $bayar)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b">{{ $bayar->nama_pembayaran }}</td>
                        <td class="py-2 px-4 border-b">
                            Rp {{ number_format($bayar->total_tagihan, 0, ',', '.') }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ \Carbon\Carbon::parse($bayar->tanggal_pembayaran)->format('d-m-Y H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
