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
        Schema::create('guru_mapel', function (Blueprint $table) {
            $table->id();
            $table->string('NIP', 50);
            $table->string('kd_mapel', 50);
            $table->timestamps();

            $table->foreign('NIP')->references('NIP')->on('guru')->onDelete('cascade');
            $table->foreign('kd_mapel')->references('kd_mapel')->on('mapel')->onDelete('cascade');
        });

        // Migrate existing data
        $gurus = DB::table('guru')->whereNotNull('kd_mapel')->get();
        foreach ($gurus as $guru) {
            DB::table('guru_mapel')->insert([
                'NIP' => $guru->NIP,
                'kd_mapel' => $guru->kd_mapel,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_mapel');
    }
};
