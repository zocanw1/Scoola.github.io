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
        Schema::create('presensi_status_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('presensi_id');
            $table->foreignId('sesi_id')->nullable()->constrained('sesi_presensis')->nullOnDelete();
            $table->string('nis', 20);
            $table->string('old_status', 50);
            $table->string('new_status', 50);
            $table->text('reason');
            $table->foreignId('changed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->foreign('presensi_id')->references('id')->on('presensi')->cascadeOnDelete();
            $table->foreign('nis')->references('NIS')->on('siswa')->cascadeOnDelete();
            $table->index(['nis', 'created_at']);
            $table->index(['sesi_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_status_histories');
    }
};
