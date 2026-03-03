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
        'keterangan'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}