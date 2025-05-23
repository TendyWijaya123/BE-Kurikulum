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
        Schema::create('kurikulums', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun_awal');
            $table->integer('tahun_akhir');
            $table->boolean('is_active')->default(false);
            $table->enum('is_sksu', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_bk', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_ipteks', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_perancangan_cpl', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_vmt', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_cpl_ppm_vm', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_matriks_cpl_ppm', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_matriks_cpl_iea', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_pengatahuan', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_matriks_cpl_p', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_peta_kompetensi', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_materi_pembelajaran', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_matriks_p_mp', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_matriks_p_mp_mk', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_mata_kuliah', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_jejaring_mata_kuliah', ['belum', 'progres', 'selesai'])->default('belum');
            $table->enum('is_matriks_cpl_mk', ['belum', 'progres', 'selesai'])->default('belum');
            $table->foreignId('prodi_id')->constrained('prodis')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['tahun_awal', 'tahun_akhir', 'prodi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurikulums');
    }
};
