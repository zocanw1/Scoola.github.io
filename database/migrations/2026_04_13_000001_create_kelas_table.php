<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 20)->unique();
            $table->string('wali_kelas_nip', 20)->nullable();
            $table->timestamps();

            $table->foreign('wali_kelas_nip')
                  ->references('NIP')
                  ->on('guru')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
