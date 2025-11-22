<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan form login admin
    public function showLoginForm()
    {
        return view('admin.login');  // Pastikan view admin.login ada
    }

    // Proses login admin
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');  // Sesuaikan jika menggunakan NIS atau username

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');  // Redirect setelah login berhasil
        }

        return back()->withErrors(['email' => 'Email atau Password salah']);
    }
}
