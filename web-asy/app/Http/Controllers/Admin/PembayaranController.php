<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\KategoriPembayaran;
use App\Models\User; // Import User agar lebih jelas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with('kategori', 'siswa');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                ->orWhere('kelas', 'like', "%{$search}%")
                ->orWhereHas('kategori', function ($k) use ($search) {
                    $k->where('nama', 'like', "%{$search}%");
                });
            });
        }

        $pembayarans = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function create()
    {
        $kategori = KategoriPembayaran::all();
        return view('admin.pembayaran.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'    => 'required|exists:kategori_pembayarans,id',
            'nama'           => 'required|string|max:255',
            'kelas'          => 'nullable|string|max:255',
            'jumlah'         => 'required|numeric',
            'tanggal_buat'   => 'required|date',
            'tanggal_tempo'  => 'nullable|date',
            'nis'            => 'required|exists:users,nis',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Format jumlah
        $validated['jumlah'] = number_format((float) $validated['jumlah'], 2, '.', '');

        // Upload foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pembayaran');
        }

        // Create pembayaran
        $pembayaran = Pembayaran::create($validated);

        // Ambil 1 siswa berdasarkan NIS
        $siswa = User::where('nis', $validated['nis'])->first();

        // Attach ke pivot HANYA untuk siswa ini
        $pembayaran->siswa()->attach([
            $siswa->id => [
                'status' => 'belum lunas',
                'tanggal_pembayaran' => null,
                'order_id' => null,
            ],
        ]);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil ditambahkan untuk siswa dengan NIS ' . $validated['nis']);
    }


    public function edit(Pembayaran $pembayaran)
    {
        $kategori = KategoriPembayaran::all();
        return view('admin.pembayaran.edit', compact('pembayaran', 'kategori'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'kategori_id'    => 'required|exists:kategori_pembayarans,id',
            'nama'           => 'required|string|max:255',
            'kelas'          => 'nullable|string|max:255',
            'jumlah'         => 'required|numeric',
            'tanggal_buat'   => 'required|date',
            'tanggal_tempo'  => 'nullable|date',
            'nis'            => 'required|exists:users,nis',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['jumlah'] = number_format((float) $validated['jumlah'], 2, '.', '');

        if ($request->hasFile('foto')) {
            if ($pembayaran->foto) {
                Storage::delete($pembayaran->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pembayaran');
        }

        $pembayaran->update($validated);

        // Hapus pivot lama
        $pembayaran->siswa()->detach();

        // Attach ulang sesuai NIS
        $siswa = User::where('nis', $validated['nis'])->first();

        $pembayaran->siswa()->attach([
            $siswa->id => [
                'status' => 'belum lunas',
                'tanggal_pembayaran' => null,
                'order_id' => null,
            ]
        ]);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil diperbarui untuk siswa dengan NIS ' . $validated['nis']);
    }

    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->foto) {
            Storage::delete($pembayaran->foto);
        }

        $pembayaran->siswa()->detach();
        $pembayaran->delete();

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
