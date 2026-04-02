<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_stoks', function (Blueprint $table) {
            $table->id();
            $table->string('no_request')->unique(); // Contoh: PO-20260319-001
            $table->foreignId('user_id')->constrained('users'); // Admin yang bikin request
            $table->date('tanggal_request');
            $table->text('keterangan')->nullable();
            
            // STATUS PERMINTAAN & GEMBOK MANAJER
            // Status bisa: 'pending' (draft admin), 'approved' (di-ACC manajer), 'rejected' (Ditolak)
            $table->string('status')->default('pending'); 
            $table->foreignId('verified_by')->nullable()->constrained('users'); // ID Manajer yang nge-ACC
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_stoks');
    }
};