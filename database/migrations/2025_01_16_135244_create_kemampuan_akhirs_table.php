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
        Schema::create('kemampuan_akhirs', function (Blueprint $table) {
            $table->id();
            $table->text('deskripsi');
            $table->integer('estimasi_beban_belajar');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kemampuan_akhirs');
    }
};
