@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-semibold mb-4">Tambah Kategori Pembayaran</h2>

    <form action="{{ route('admin.kategori.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input type="text" name="nama" id="nama" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"></textarea>
        </div>

        <div class="mb-4">
            <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe Pembayaran</label>
            <select name="tipe" id="tipe" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" required>
                <option value="Harian">Harian</option>
                <option value="Bulanan">Bulanan</option>
                <option value="Tahunan">Tahunan</option>
                <option value="Bebas">Bebas</option>
            </select>
        </div>

        <button type="submit" class="bg-emerald-600 text-white py-2 px-4 rounded hover:bg-emerald-700">Simpan</button>
    </form>
@endsection
