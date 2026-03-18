<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_pelanggans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Nyatet anak marketing siapa yang input
            
            $table->string('no_pesanan')->unique(); // Contoh: ORD-20260317-001
            $table->string('nama_pelanggan'); 
            $table->date('tanggal_pesanan'); 
            $table->string('tipe'); // Sesuai class diagram (Misal: B2B, B2C, Eceran, dll)
            
            // GEMBOK DIGITAL (Implementasi fitur kirimData)
            // false = Masih draft (Tim Marketing masih bisa ngedit/nambah barang)
            // true = Sudah terkirim ke Admin (Terkunci permanen)
            $table->boolean('is_sent')->default(false); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_pelanggans');
    }
};