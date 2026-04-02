<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPermintaanStok extends Model
{
    use HasFactory;

    protected $fillable = [
        'permintaan_stok_id', 'barang_id', 'jumlah_unit', 'jumlah_berat'
    ];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanStok::class, 'permintaan_stok_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
