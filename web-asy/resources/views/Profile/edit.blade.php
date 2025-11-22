@extends('layouts.admin')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-3xl mx-auto">

    <h2 class="text-2xl font-bold mb-6">Edit Profil</h2>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow border space-y-5">
        @csrf
        @method('PUT')

        {{-- Notifikasi --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Avatar preview --}}
        <div class="flex items-center gap-4">
            @php $avatar = auth()->user()->avatar_path ?? null; @endphp
            <img src="{{ $avatar ? asset('storage/'.$avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&size=96' }}"
                 alt="Avatar"
                 class="h-16 w-16 rounded-full object-cover ring-2 ring-green-200">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil (opsional)</label>
                <input type="file" name="avatar" accept="image/*" class="block w-full text-sm border rounded px-3 py-2">
                <p class="text-xs text-gray-500 mt-1">JPG/PNG/WEBP &le; 2MB</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border rounded px-3 py-2">
            </div>

            {{-- Kelas (tampilkan jika ada kolom kelas) --}}
            @if(Schema::hasColumn('users','kelas'))
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Kelas (opsional)</label>
                    <select name="kelas" class="w-full border rounded px-3 py-2">
                        <option value="">— Pilih / kosongkan —</option>
                        @forelse($kelasOptions as $k)
                            <option value="{{ $k }}" {{ old('kelas', $user->kelas) == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @empty
                            <option value="{{ old('kelas', $user->kelas) }}" selected>{{ old('kelas', $user->kelas) }}</option>
                        @endforelse
                    </select>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Password Baru (opsional)</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" placeholder="Kosongkan bila tidak ganti">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white rounded px-5 py-2">Simpan Perubahan</button>
            <a href="{{ url()->previous() }}" class="text-gray-600 hover:underline">Batal</a>
        </div>
    </form>
</div>
@endsection
