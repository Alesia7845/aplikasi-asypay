<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan form login siswa
    public function showLoginForm()
    {
        return view('siswa.login');  // Pastikan view siswa.login ada
    }

    // Proses login siswa
    public function login(Request $request)
    {
        // Validate the input
        $request->validate([
            'nis' => 'required',  // NIS is used as username
            'password' => 'required|min:6',
        ]);

        // Attempt to log in using NIS (or username)
        $credentials = $request->only('nis', 'password');

        if (Auth::guard('siswa')->attempt($credentials)) {
            return redirect()->route('siswa.dashboard');  // Redirect after successful login
        }

        return back()->withErrors(['nis' => 'NIS atau Password salah']);
    }

    // Logout siswa
    public function logout()
    {
        Auth::guard('siswa')->logout();
        return redirect()->route('siswa.login');
    }
}
