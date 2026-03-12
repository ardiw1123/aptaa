<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CekStok extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_cek',
        'barang_id',
        'user_id',
        'stok_ekor_sistem',
        'stok_berat_sistem',
        'stok_ekor_fisik',
        'stok_berat_fisik',
        'catatan',
        'is_verified', 
        'verified_by', 
        'verified_at'
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Relasi ke User (Tim Barang yang bertugas)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relasi ke User (Admin yang memverifikasi)
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}