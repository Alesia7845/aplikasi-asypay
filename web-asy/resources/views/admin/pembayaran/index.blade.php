@extends('layouts.admin')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="container mx-auto px-4 mt-6">
    <h1 class="text-2xl font-bold mb-6">Daftar Pembayaran</h1>

    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('admin.pembayaran.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
            + Tambah Pembayaran
        </a>

        <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="flex items-center space-x-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama, kelas, kategori..."
                class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring focus:ring-blue-300"
            />
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded">
                Cari
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded shadow border border-gray-300">
        <table class="min-w-full bg-white">
            <thead class="bg-blue-100 text-left">
                <tr>
                    <th class="py-3 px-4 border-b">#</th>
                    <th class="py-3 px-4 border-b">Nama</th>
                    <th class="py-3 px-4 border-b">NIS</th>
                    <th class="py-3 px-4 border-b">Kategori</th>
                    <th class="py-3 px-4 border-b">Kelas</th>
                    <th class="py-3 px-4 border-b">Jumlah</th>
                    <th class="py-3 px-4 border-b">Tanggal Buat</th>
                    <th class="py-3 px-4 border-b">Tanggal Tempo</th>
                    <th class="py-3 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pembayarans as $index => $pembayaran)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b">{{ $pembayarans->firstItem() + $index }}</td>
                        <td class="py-2 px-4 border-b">{{ $pembayaran->nama }}</td>
                        <td class="py-2 px-4 border-b">{{ $pembayaran->nis }}</td>
                        <td class="py-2 px-4 border-b">{{ $pembayaran->kategori->nama ?? '-' }}</td>
                        <td class="py-2 px-4 border-b">{{ $pembayaran->kelas ?? '' }}</td>
                        <td class="py-2 px-4 border-b">Rp {{ number_format($pembayaran->jumlah, 2, ',', '.') }}</td>
                        <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($pembayaran->tanggal_buat)->format('d-m-Y') }}</td>
                        <td class="py-2 px-4 border-b">
                            {{ $pembayaran->tanggal_tempo ? \Carbon\Carbon::parse($pembayaran->tanggal_tempo)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="py-2 px-4 border-b space-x-2">
                            <a href="{{ route('admin.pembayaran.edit', $pembayaran->id) }}" 
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                            Edit
                            </a>
                            <form action="{{ route('admin.pembayaran.destroy', $pembayaran->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus pembayaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-4 text-center text-gray-500">Tidak ada data pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pembayarans->withQueryString()->links() }}
    </div>
</div>
@endsection
