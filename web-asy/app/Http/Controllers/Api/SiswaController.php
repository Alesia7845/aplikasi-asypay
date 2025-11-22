<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function getNama(Request $request)
    {
        $nis = $request->nis;

        if (!$nis) {
            return response()->json([
                'success' => false,
                'nama' => null,
                'message' => 'NIS tidak ditemukan'
            ]);
        }

        $user = Siswa::where('nis', $nis)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'nama' => null,
                'message' => 'User tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'nama' => $user->name
        ]);
    }
}
