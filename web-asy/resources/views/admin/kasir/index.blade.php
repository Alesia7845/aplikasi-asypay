@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Daftar Pembayaran</h1>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b text-left">Nama Pembayaran</th>
                <th class="py-2 px-4 border-b text-left">Nama Siswa</th>
                <th class="py-2 px-4 border-b text-left">Kelas</th>
                <th class="py-2 px-4 border-b text-left">Jumlah Pembayaran</th>
                <th class="py-2 px-4 border-b text-left">Tanggal Tempo</th>
                <th class="py-2 px-4 border-b text-left">Status</th>
                <th class="py-2 px-4 border-b text-left">Tanggal Bayar</th>
                <th class="py-2 px-4 border-b text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pembayarans as $pembayaran)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $pembayaran->nama_pembayaran ?? '-' }}</td>
                    <td class="py-2 px-4 border-b">{{ $pembayaran->nama_siswa ?? 'Nama Tidak Ditemukan' }}</td>
                    <td class="py-2 px-4 border-b">{{ $pembayaran->kelas ?? '-' }}</td>
                    <td class="py-2 px-4 border-b">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                    <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($pembayaran->tanggal_tempo)->format('d-m-Y') }}</td>
                    <td class="py-2 px-4 border-b">
                        <span class="px-2 py-1 rounded 
                            @if($pembayaran->status == 'lunas') bg-green-500 
                            @elseif($pembayaran->status == 'menunggu-verifikasi') bg-yellow-500 
                            @else bg-red-500 @endif
                            text-white">
                            {{ ucfirst(str_replace('-', ' ', $pembayaran->status)) }}
                        </span>
                    </td>
                    <td class="py-2 px-4 border-b">
                        {{ $pembayaran->tanggal_bayar
                            ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d-m-Y H:i')
                            : '-' }}
                    </td>
                    <td class="py-2 px-4 border-b">
                        @if(in_array($pembayaran->status, ['belum-lunas', 'menunggu-verifikasi']))
                            {{-- GANTI: route('kasir.bayarForm') → route('admin.kasir.bayarForm') --}}
                            <a href="{{ route('admin.kasir.bayarForm', $pembayaran->pivot_id) }}"
                               class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                Bayar
                            </a>
                        @elseif($pembayaran->status == 'lunas')
                            <span class="text-gray-500">Lunas</span>
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="py-4 px-4 text-center text-gray-500">
                        Tidak ada data pembayaran.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $pembayarans->links() }}
    </div>
</div>
@endsection
