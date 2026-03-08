<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjualan_id', 'barang_id', 'jumlah_unit', 'jumlah_berat', 'harga_satuan', 'subtotal'
    ];

    // Relasi balik ke Penjualan (Header)
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    // Relasi ke Barang yang dibeli
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
