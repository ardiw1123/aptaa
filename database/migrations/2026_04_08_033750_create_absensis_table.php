<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('absensis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->date('tanggal');
        $table->time('jam_masuk')->nullable();
        $table->time('jam_keluar')->nullable();
        $table->string('lokasi_masuk')->nullable(); // Nyimpen Latitude,Longitude
        $table->string('lokasi_keluar')->nullable();
        $table->string('status')->default('Hadir'); // Tepat Waktu, Terlambat, dll
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
