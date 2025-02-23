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
        Schema::create('rps_matakuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->nullable()->constrained('mata_kuliahs')->onDelete('cascade');
            $table->foreignId('kemampuan_akhir_id')->nullable()->constrained('kemampuan_akhirs')->onDelete('set null');
            $table->integer('minggu');
            $table->text('pokok_bahasan')->nullable();
            $table->text('modalitas_bentuk_strategi_metodepembelajaran')->nullable();
            $table->text('instrumen_penilaian')->nullable();
            $table->text('hasil_belajar')->nullable();
            $table->foreignId('tujuan_belajar_id')->nullable()->constrained('tujuan_belajars')->onDelete('set null');
            $table->foreignId('cpl_id')->nullable()->constrained('cpls')->onDelete('set null');
            $table->integer('bobot_penilaian')->nullable();
            $table->timestamps();

            $table->unique(['mata_kuliah_id', 'minggu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rps_matakuliah');
    }
};
