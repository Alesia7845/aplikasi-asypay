@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-8">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Siswa</h1>
        <p class="text-gray-600 mt-1">Selamat datang! Berikut ringkasan pembayaran terbaru kamu.</p>

        <!-- GAMBAR -->
        <div class="mt-4">
            <img 
                src="{{ asset('images/landing.jpg') }}" 
                alt="Dashboard Banner" 
                class="w-full h-64 object-cover rounded-xl shadow-md border"
            >
        </div>
    </div>

    <!-- CARD 5 PEMBAYARAN TERAKHIR -->
    <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <span class="inline-block w-2 h-5 bg-blue-500 rounded"></span>
            5 Pembayaran Terakhir (Lunas)
        </h2>

        @if($siswaBayar->isEmpty())
            <p class="text-gray-600 italic">Belum ada pembayaran yang lunas.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-sm">
                            <th class="py-3 px-4 border-b text-left font-semibold">Nama Pembayaran</th>
                            <th class="py-3 px-4 border-b text-left font-semibold">Total Tagihan</th>
                            <th class="py-3 px-4 border-b text-left font-semibold">Tanggal Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @foreach($siswaBayar as $bayar)
                            <tr class="hover:bg-blue-50 transition">
                                <td class="py-3 px-4 border-b">{{ $bayar->nama_pembayaran }}</td>
                                <td class="py-3 px-4 border-b">
                                    <span class="font-medium text-green-600">
                                        Rp {{ number_format($bayar->total_tagihan, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b">
                                    {{ \Carbon\Carbon::parse($bayar->tanggal_pembayaran)->format('d-m-Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
