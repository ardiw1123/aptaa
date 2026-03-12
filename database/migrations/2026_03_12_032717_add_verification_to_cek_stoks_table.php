<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cek_stoks', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('catatan');
            $table->foreignId('verified_by')->nullable()->constrained('users')->after('is_verified');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('cek_stoks', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['is_verified', 'verified_by', 'verified_at']);
        });
    }
};
