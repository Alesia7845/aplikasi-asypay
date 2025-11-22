<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Siswa extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'siswa';

    protected $fillable = [
        'name',
        'nis',
        'kelas',
        'tahun_ajaran',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
}
