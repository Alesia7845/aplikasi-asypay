@extends('layouts.admin')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Pembayaran</h1>

    {{-- Toolbar responsif: tombol tambah + form pencarian & filter --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <a href="{{ route('admin.pembayaran.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
            + Tambah Pembayaran
        </a>

        {{-- Tambahkan ID pada form agar mudah diakses di JavaScript --}}
        <form action="{{ route('admin.pembayaran.index') }}" method="GET"
              id="filterPembayaranForm" class="flex flex-wrap items-center gap-2">

            {{-- Filter Kategori (Otomatis) --}}
            <select name="kategori" id="kategoriSelect" class="border border-gray-300 rounded px-3 py-1">
                <option value="">Semua Kategori</option>
                {{-- Asumsi Anda memiliki variabel $kategoriList yang berisi daftar kategori --}}
                @isset($kategoriList)
                    @foreach($kategoriList as $kategoriId => $kategoriNama)
                        <option value="{{ $kategoriId }}" {{ request('kategori') == $kategoriId ? 'selected' : '' }}>
                            {{ $kategoriNama }}
                        </option>
                    @endforeach
                @endisset
            </select>

            {{-- Filter Kelas (Otomatis) --}}
            <select name="kelas" id="kelasSelect" class="border border-gray-300 rounded px-3 py-1">
                <option value="">Semua Kelas</option>
                {{-- Asumsi Anda memiliki variabel $kelasList yang berisi daftar kelas --}}
                @isset($kelasList)
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                            {{ $kelas }}
                        </option>
                    @endforeach
                @endisset
            </select>

            {{-- Input Pencarian Umum (Perlu klik Cari) --}}
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

            @if(request()->filled('search') || request()->filled('kategori') || request()->filled('kelas'))
                <a href="{{ route('admin.pembayaran.index') }}"
                   class="px-3 py-1 border rounded text-gray-700">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded shadow-sm">
            <thead class="bg-blue-100 text-left">
                <tr>
                    <th class="py-3 px-4 border-b">#</th>
                    <th class="py-3 px-4 border-b">Nama Pembayaran</th>
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
                            <form action="{{ route('admin.pembayaran.destroy', $pembayaran->id) }}"
                                  method="POST" class="inline-block"
                                  onsubmit="return confirm('Yakin hapus pembayaran ini?')">
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

{{-- Tambahkan script JavaScript di sini --}}
@push('scripts')
<script>
    // Ambil elemen select dan form
    const kategoriSelect = document.getElementById('kategoriSelect');
    const kelasSelect = document.getElementById('kelasSelect');
    const filterPembayaranForm = document.getElementById('filterPembayaranForm');

    // Fungsi untuk mengirimkan form
    const submitFilter = () => {
        filterPembayaranForm.submit();
    };
         frckdgsx    
    // Tambahkan event listener untuk Kategori
    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', submitFilter);
    }

    // Tambahkan event listener untuk Kelas
    if (kelasSelect) {
        kelasSelect.addEventListener('change', submitFilter);
    }
</script>
@endpush

@endsection






