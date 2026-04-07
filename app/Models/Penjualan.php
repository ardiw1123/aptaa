<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_invoice', 'user_id', 'nama_pembeli', 'tanggal_transaksi', 'total_harga', 'keterangan'
    ];

    // Relasi ke User (Admin yang input)
    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 1 Penjualan punya BANYAK Detail Penjualan
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }
}
