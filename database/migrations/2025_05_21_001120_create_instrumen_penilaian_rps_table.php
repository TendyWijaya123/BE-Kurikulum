<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('instrumen_penilaian_rps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_id')->constrained('rps_matakuliah')->onDelete('cascade');
            $table->enum('jenis_evaluasi', ['Quiz', 'Project', 'Case Study', 'Tugas']);
            $table->text('deskripsi');
            $table->integer('bobot_penilaian');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrumen_penilaian_rps');
    }
};
