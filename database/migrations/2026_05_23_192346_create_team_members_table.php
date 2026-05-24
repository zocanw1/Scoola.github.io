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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role'); // TEAM LEAD, MEMBER
            $table->string('kelas'); // XI-SIJA 2
            $table->string('nis');
            $table->string('birthplace');
            $table->date('birthdate');
            $table->string('phone');
            $table->text('description');
            $table->text('jobdesk');
            $table->string('photo')->nullable(); // nama file foto
            $table->json('skills')->nullable(); // ["WEB DEV", "DESIGN"]
            $table->string('sticker_bg')->default('#FDCB6E'); // warna sticker
            $table->string('img_bg')->default('#6C5CE7'); // warna background image
            $table->string('role_color')->default('#FF7675'); // warna role text
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
