<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('admin.profile.show', compact('siswa'));
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('admin.profile.edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $siswa = Siswa::findOrFail($id);
        $siswa->email = $request->email;
        $siswa->password = bcrypt($request->password);
        $siswa->save();

        return redirect()->route('admin.profile.show', $siswa->id);
    }
}