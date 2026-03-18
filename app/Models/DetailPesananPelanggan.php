<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesananPelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_pelanggan_id', 'barang_id', 'jumlah_unit', 'jumlah_berat'
    ];

    // Relasi balik ke Header Pesanan
    public function pesanan()
    {
        return $this->belongsTo(PesananPelanggan::class, 'pesanan_pelanggan_id');
    }

    // Relasi ke Master Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}