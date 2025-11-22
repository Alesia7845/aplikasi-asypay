<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsSiswa
{
    /**
     * Menangani permintaan masuk melalui middleware.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Memeriksa apakah pengguna sudah login dan memiliki peran 'siswa'
        if (Auth::check() && Auth::user()->role != 'Siswa') {
           
        }

        return $next($request); // Jika siswa, lanjutkan permintaan
    }
}
