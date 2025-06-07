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
            $table->text('kemampuan_akhir')->nullable();
            $table->integer('minggu');
            $table->enum('kategori', ['ETS', 'EAS', 'Reguler'])->default('Reguler');
            $table->text('pokok_bahasan')->nullable();
            $table->text('hasil_belajar')->nullable();
            $table->text('modalitas_pembelajaran')->nullable();
            $table->text('strategi_pembelajaran')->nullable();
            $table->text('bentuk_pembelajaran')->nullable();
            $table->text('metode_pembelajaran')->nullable();
            $table->text('media_pembelajaran')->nullable();
            $table->text('sumber_belajar')->nullable();

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
