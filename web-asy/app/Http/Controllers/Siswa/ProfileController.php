<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Profile::all();
        return view('siswa.profile.index', compact('profiles'));
    }

    public function create()
    {
        return view('siswa.profile.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'nis' => 'required|numeric|digits_between:1,20',
            'alamat' => 'required|string',
        ]);

        Profile::create($validated);
        return redirect()->route('profile.index')->with('success', 'Profil berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $profile = Profile::findOrFail($id);
        return view('siswa.profile.edit', compact('profile'));
    }

    public function update(Request $request, $id)
    {
        $profile = Profile::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'nis' => 'required|numeric|digits_between:1,20',
            'alamat' => 'required|string',
        ]);

        $profile->update($validated);
        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $profile = Profile::findOrFail($id);
        $profile->delete();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil dihapus.');
    }
}
