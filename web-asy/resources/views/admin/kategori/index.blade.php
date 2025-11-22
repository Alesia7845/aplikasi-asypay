@extends('layouts.admin')

@section('content')
   <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold">Manajemen Kategori Pembayaran</h2>
    <form action="{{ route('admin.kategori.index') }}" method="GET" class="flex items-center">
        <input type="text" name="search" placeholder="Cari kategori..." value="{{ request('search') }}"
            class="border rounded px-3 py-1 mr-2 focus:outline-none focus:ring focus:border-blue-300">
        <button type="submit" class="bg-blue-600 text-white py-1 px-3 rounded hover:bg-blue-700">Cari</button>
    </form>
    <a href="{{ route('admin.kategori.create') }}" class="ml-4 bg-emerald-600 text-white py-2 px-4 rounded hover:bg-emerald-700">Tambah Kategori</a>
</div>


    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left border-b">No</th>
                <th class="px-4 py-2 text-left border-b">Nama Kategori</th>
                <th class="px-4 py-2 text-left border-b">Deskripsi</th>
                <th class="px-4 py-2 text-left border-b">Tipe Pembayaran</th>
                <th class="px-4 py-2 text-left border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kategori as $key => $item)
                <tr>
                    <td class="px-4 py-2 border-b">{{ $key + 1 }}</td>
                    <td class="px-4 py-2 border-b">{{ $item->nama }}</td>
                    <td class="px-4 py-2 border-b">{{ $item->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                    <td class="px-4 py-2 border-b">{{ $item->tipe }}</td>
                    <td class="px-4 py-2 border-b">
                        <a href="{{ route('admin.kategori.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a> | 
                        <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin ingin hapus kategori ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
