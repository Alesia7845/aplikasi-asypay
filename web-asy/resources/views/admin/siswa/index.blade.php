@extends('layouts.admin')

@section('title', 'Manajemen Siswa')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Siswa</h1>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <a href="{{ route('admin.siswa.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
            + Tambah Siswa
        </a>

        {{-- Beri ID pada form agar mudah diakses di JavaScript --}}
        <form action="{{ route('admin.siswa.index') }}" method="GET"
              id="filterForm" class="flex flex-wrap items-center gap-2">
            {{-- Search umum: nama / NIS / email. Jika isi angka murni (1/2/12), dianggap kelas exact juga --}}
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / NIS / email / angka=kelas"
                   class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring focus:ring-blue-300" />

            {{-- Filter Kelas (exact) --}}
            {{-- Tambahkan ID dan event onchange --}}
            <select name="kelas" id="kelasSelect" class="border border-gray-300 rounded px-3 py-1">
                <option value="">Semua Kelas</option>
                @isset($kelasList)
                @foreach($kelasList as $k)
                    <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>
                        {{ $k }}
                    </option>
                @endforeach
                @endisset
            </select>

            {{-- Filter Tahun Ajaran (exact) --}}
            {{-- Tambahkan ID dan event onchange --}}
            <select name="tahun_ajaran" id="tahunAjaranSelect" class="border border-gray-300 rounded px-3 py-1">
                <option value="">Semua Tahun</option>
                @isset($tahunList)
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ request('tahun_ajaran') == $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                @endforeach
                @endisset
            </select>

            {{-- Tombol Cari ini masih ada, tapi tidak perlu diklik jika menggunakan dropdown --}}
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded">
                Cari
            </button>

            @if(request()->hasAny(['search','kelas','tahun_ajaran']))
                <a href="{{ route('admin.siswa.index') }}" class="px-3 py-1 border rounded text-gray-700">
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
            <thead class="bg-blue-100">
                <tr>
                    <th class="py-2 px-4 border-b text-left">Nama</th>
                    <th class="py-2 px-4 border-b text-left">NIS</th>
                    <th class="py-2 px-4 border-b text-left">Kelas</th>
                    <th class="py-2 px-4 border-b text-left">Email</th>
                    <th class="py-2 px-4 border-b text-left">Tahun Ajaran</th>
                    <th class="py-2 px-4 border-b text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswas as $siswa)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b">{{ $siswa->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $siswa->nis }}</td>
                    <td class="py-2 px-4 border-b">{{ $siswa->kelas }}</td>
                    <td class="py-2 px-4 border-b">{{ $siswa->email }}</td>
                    <td class="py-2 px-4 border-b">{{ $siswa->tahun_ajaran }}</td>
                    <td class="py-2 px-4 border-b space-x-2">
                        <a href="{{ route('admin.siswa.edit', $siswa->id) }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</a>
                        <form action="{{ route('admin.siswa.destroy', $siswa->id) }}"
                              method="POST" class="inline-block"
                              onsubmit="return confirm('Yakin hapus siswa ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-4 text-center text-gray-500">Tidak ada data siswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $siswas->links() }}
    </div>
</div>

{{-- Tambahkan script JavaScript di sini --}}
@push('scripts')
<script>
    // Ambil elemen select dan form
    const kelasSelect = document.getElementById('kelasSelect');
    const tahunAjaranSelect = document.getElementById('tahunAjaranSelect');
    const filterForm = document.getElementById('filterForm');

    // Tambahkan event listener untuk Kelas
    if (kelasSelect) {
        kelasSelect.addEventListener('change', function() {
            // Saat nilai Kelas berubah, kirim (submit) form
            filterForm.submit();
        });
    }

    // Tambahkan event listener untuk Tahun Ajaran
    if (tahunAjaranSelect) {
        tahunAjaranSelect.addEventListener('change', function() {
            // Saat nilai Tahun Ajaran berubah, kirim (submit) form
            filterForm.submit();
        });
    }
</script>
@endpush
{{-- Pastikan layout Anda (layouts.admin) memiliki @stack('scripts') sebelum tag </body> --}}

@endsection