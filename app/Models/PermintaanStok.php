<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanStok extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_request', 'user_id', 'tanggal_request', 'keterangan', 
        'status', 'verified_by', 'verified_at'
    ];

    // Relasi ke Admin yang bikin
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Manajer yang nge-ACC
    public function manajer()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Relasi ke tabel detail
    public function detailPermintaan()
    {
        return $this->hasMany(DetailPermintaanStok::class);
    }
}
