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
        Schema::table('sesi_presensis', function (Blueprint $table) {
            $table->string('kd_jp', 20)->nullable()->after('kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sesi_presensis', function (Blueprint $table) {
            $table->dropColumn('kd_jp');
        });
    }
};
