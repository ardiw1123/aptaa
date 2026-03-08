<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique(); // Buat nomor struk unik, misal: INV-20260303-001
            $table->foreignId('user_id')->constrained('users'); // Nyatet Admin siapa yang ngelayanin
            $table->string('nama_pembeli')->nullable(); // Opsional, kalau pembeli mau dicatet namanya
            $table->datetime('tanggal_transaksi'); // Pake datetime biar dapet jam transaksinya juga
            $table->decimal('total_harga', 15, 2)->default(0); // Total dari seluruh belanjaan
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};