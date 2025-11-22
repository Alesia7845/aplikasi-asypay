<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPembayaran;
use Illuminate\Http\Request;

class KategoriPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriPembayaran::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama', 'like', "%$search%")
                ->orWhere('deskripsi', 'like', "%$search%");
        }

        $kategori = $query->orderBy('nama')->get();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:Harian,Bulanan,Tahunan,Bebas',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        KategoriPembayaran::create([
            'nama' => $request->nama,
            'tipe' => $request->tipe,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(KategoriPembayaran $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriPembayaran $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:Harian,Bulanan,Tahunan,Bebas',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $kategori->update([
            'nama' => $request->nama,
            'tipe' => $request->tipe,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriPembayaran $kategori)
    {
        $kategori->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
