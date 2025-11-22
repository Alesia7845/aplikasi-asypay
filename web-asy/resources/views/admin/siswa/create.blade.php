@extends('layouts.admin')

@section('title', 'Tambah Siswa')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-semibold mb-4">Tambah Siswa</h1>

    <form action="{{ route('admin.siswa.store') }}" method="POST">
        @csrf

        <!-- Nama -->
        <div class="mb-4">
            <label for="name" class="block font-medium">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" value="{{ old('name') }}" required>
            @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block font-medium">Email</label>
            <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2" value="{{ old('email') }}">
            @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- NIS -->
        <div class="mb-4">
            <label for="nis" class="block font-medium">NIS</label>
            <input type="text" name="nis" id="nis" class="w-full border rounded px-3 py-2" value="{{ old('nis') }}">
            @error('nis') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Kelas -->
        <div class="mb-4">
            <label for="kelas" class="block font-medium">Kelas</label>
            <input type="text" name="kelas" id="kelas" class="w-full border rounded px-3 py-2" value="{{ old('kelas') }}">
        </div>

        <!-- Tahun Ajaran -->
        <div class="mb-4">
            <label for="tahun_ajaran" class="block font-medium">Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" id="tahun_ajaran" class="w-full border rounded px-3 py-2" value="{{ old('tahun_ajaran') }}">
        </div>

        <!-- Role -->
        <div class="mb-4">
            <label for="role" class="block font-medium">Role</label>
            <select name="role" id="role" class="w-full border rounded px-3 py-2">
                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block font-medium">Password <span class="text-red-500">*</span></label>
            <input type="password" name="password" id="password" class="w-full border rounded px-3 py-2" required>
            @error('password') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Tombol -->
        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
            <a href="{{ route('admin.siswa.index') }}" class="text-gray-600 hover:underline">Batal</a>
        </div>
    </form>
</div>
@endsection
