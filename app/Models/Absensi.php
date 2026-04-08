<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    // WAJIB: Kasih tau Laravel kolom mana aja yang boleh di-insert data
    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'lokasi_masuk',
        'lokasi_keluar',
        'status'
    ];

    // Relasi balik ke tabel User (1 Absensi punya 1 Pegawai)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}