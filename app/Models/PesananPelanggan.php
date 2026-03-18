<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananPelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'no_pesanan', 'nama_pelanggan', 'tanggal_pesanan', 'tipe', 'is_sent'
    ];

    // Relasi ke User (Marketing)
    public function marketing()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Tabel Detail (1 Pesanan punya BANYAK detail barang)
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesananPelanggan::class);
    }
}
