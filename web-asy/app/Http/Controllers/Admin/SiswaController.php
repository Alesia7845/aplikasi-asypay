<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class SiswaController extends Controller
{
    // Tampilkan daftar siswa dengan fitur pencarian & pagination + filter kelas & tahun
    public function index(Request $request)
    {
        $query = User::where('role', 'siswa');

        // ------ Search umum: nama/nis/email ------
        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('nis', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });

            // Jika search angka murni (1-2 digit), treat sebagai filter kelas exact juga
            if (preg_match('/^\d{1,2}$/', $search)) {
                $query->orWhere('kelas', $search);
            }
        }

        // ------ Filter kelas exact (opsional lewat dropdown) ------
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        // ------ Filter tahun ajaran exact (opsional lewat dropdown) ------
        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        // Data untuk dropdown
        $kelasList = User::where('role', 'siswa')
            ->whereNotNull('kelas')
            ->select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        $tahunList = User::where('role', 'siswa')
            ->whereNotNull('tahun_ajaran')
            ->select('tahun_ajaran')->distinct()->orderBy('tahun_ajaran', 'desc')->pluck('tahun_ajaran');

        $siswas = $query->orderBy('kelas')->orderBy('name')
            ->paginate(10)
            ->appends($request->query()); // keep query saat paginate

        return view('admin.siswa.index', compact('siswas', 'kelasList', 'tahunList'));
    }

    // Form tambah siswa
    public function create()
    {
        return view('admin.siswa.create');
    }

    // Simpan siswa baru
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'email'         => 'nullable|email|unique:users,email',
            'nis'           => 'nullable|unique:users,nis',
            'password'      => 'required|min:6',
            'kelas'         => 'required',
            'tahun_ajaran'  => 'required',
        ]);

        $user = User::create([
        'name'          => $request->name,
        'nis'           => $request->nis,
        'kelas'         => $request->kelas,
        'role'          => 'siswa',
        'tahun_ajaran'  => $request->tahun_ajaran,
        'email'         => $request->email,
        'password'      => Hash::make($request->password),
        ]);

        Siswa::create([
            'name'      => $request->name,
            'nis'       => $request->nis,
            'kelas'         => $request->kelas,
            'tahun_ajaran'  => $request->tahun_ajaran,
            'email'         => $request->email,
            'password'  => Hash::make($request->password),
        ]);


        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    // Form edit siswa
    public function edit(User $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    // Update data siswa
    public function update(Request $request, User $siswa)
    {
        $request->validate([
            'name'          => 'required',
            'email'         => 'nullable|email|unique:users,email,' . $siswa->id,
            'nis'           => 'nullable|unique:users,nis,' . $siswa->id,
            'kelas'         => 'required',
            'tahun_ajaran'  => 'required',
            // password boleh kosong (tidak diubah)
        ]);

        $siswa->update([
            'name'          => $request->name,
            'nis'           => $request->nis,
            'kelas'         => $request->kelas,
            'role'          => $request->role ?? 'siswa',
            'tahun_ajaran'  => $request->tahun_ajaran,
            'email'         => $request->email,
            'password'      => $request->filled('password')
                                ? Hash::make($request->password)
                                : $siswa->password,
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    // Hapus siswa
    public function destroy(User $siswa)
    {
        $siswa->delete();
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
