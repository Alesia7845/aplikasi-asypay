<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        // Ambil opsi kelas dari tabel users (jika ada kolom 'kelas')
        $kelasOptions = collect();
        if (Schema::hasColumn('users', 'kelas')) {
            $kelasOptions = DB::table('users')
                ->whereNotNull('kelas')
                ->select('kelas')
                ->groupBy('kelas')
                ->orderBy('kelas')
                ->pluck('kelas');
        }

        return view('profile.edit', compact('user', 'kelasOptions'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // Validasi dasar
        $rules = [
            'name'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'avatar'=> ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => ['nullable', 'min:6', 'confirmed'],
        ];

        // Jika ada kolom 'kelas', izinkan update
        if (Schema::hasColumn('users', 'kelas')) {
            $rules['kelas'] = ['nullable', 'string', 'max:50'];
        }

        $data = $request->validate($rules);

        // Update field dasar
        $user->name  = $data['name'];
        $user->email = $data['email'];

        if (Schema::hasColumn('users', 'kelas')) {
            $user->kelas = $data['kelas'] ?? $user->kelas;
        }

        // Password (opsional)
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // Avatar (opsional)
        if ($request->hasFile('avatar')) {
            // Hapus lama jika ada
            if (!empty($user->avatar_path) && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            // Pastikan kolom avatar_path ada; kalau belum, kamu bisa tambah migration
            if (Schema::hasColumn('users', 'avatar_path')) {
                $user->avatar_path = $path;
            }
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
