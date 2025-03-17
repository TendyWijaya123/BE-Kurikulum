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
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->enum('kategori', ["Institusi", "Prodi", "Nasional"]);
            $table->text('deskripsi_singkat')->nullable();
            $table->text('tujuan');
            $table->integer('semester')->nullable();
            $table->integer('teori_bt')->nullable()->default(0);
            $table->integer('teori_pt')->nullable()->default(0);
            $table->integer('teori_m')->nullable()->default(0);
            $table->integer('total_teori')->nullable()->default(0);
            $table->integer('praktek_bt')->nullable()->default(0);
            $table->integer('praktek_pt')->nullable()->default(0);
            $table->integer('praktek_m')->nullable()->default(0);
            $table->integer('total_praktek')->nullable()->default(0);
            $table->decimal('sks', 5, 1)->default(0);
            $table->integer('total_beban_belajar')->default(0);
            $table->foreignId('kurikulum_id')->constrained('kurikulums')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};
