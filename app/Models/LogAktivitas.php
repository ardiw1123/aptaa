<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    // WAJIB: Buka gembok mass assignment
    protected $fillable = [
        'user_id',
        'modul',
        'aktivitas'
    ];

    // Relasi balik ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}