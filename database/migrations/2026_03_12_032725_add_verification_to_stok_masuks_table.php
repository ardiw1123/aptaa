<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_masuks', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('jumlah_berat');
            $table->foreignId('verified_by')->nullable()->constrained('users')->after('is_verified');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('stok_masuks', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['is_verified', 'verified_by', 'verified_at']);
        });
    }
};
