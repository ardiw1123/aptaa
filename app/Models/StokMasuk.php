<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id', 
        'user_id', 
        'tanggal_masuk', 
        'jumlah_unit',  // Kolom EKOR di Sheets
        'jumlah_berat', // Kolom KG/LITER di Sheets
        'nama_hub',     // Contoh: MUNDU
        'keterangan',
        'is_verified', 
        'verified_by', 
        'verified_at'
    ];

    // Relasi ke User (Admin/Manajer yang verifikasi)
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}