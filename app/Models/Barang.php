<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 
        'nama_barang', 
        'kategori', 
        'satuan_utama', // Ekor, Kg, atau Liter
        'stok_ekor',    // Saldo jumlah unit
        'stok_berat'    // Saldo jumlah berat/volume
    ];

    public function stokMasuks()
    {
        return $this->hasMany(StokMasuk::class);
    }
}