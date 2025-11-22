<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URL yang dikecualikan dari verifikasi CSRF.
     * Tambahkan semua kemungkinan endpoint webhook Midtrans agar tidak terblokir.
     */
   
}
