<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\KategoriPembayaran;
use Illuminate\Http\Request;

class KategoriPembayaranController extends Controller
{
    public function index()
    {
        $kategori = KategoriPembayaran::all();
        return view('siswa.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('siswa.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tipe' => 'required|in:Harian,Bulanan,Tahunan,Bebas',
        ]);

        KategoriPembayaran::create($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori ditambahkan');
    }

    public function edit(KategoriPembayaran $kategori)
    {
        return view('siswa.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriPembayaran $kategori)
    {
        $request->validate([
            'nama' => 'required',
            'tipe' => 'required|in:Harian,Bulanan,Tahunan,Bebas',
        ]);

        $kategori->update($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori diperbarui');
    }

    public function destroy(KategoriPembayaran $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori dihapus');
    }
}
