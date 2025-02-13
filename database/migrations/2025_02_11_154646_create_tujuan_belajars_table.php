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
        Schema::create('tujuan_belajars', function (Blueprint $table) {
            $table->id();
            $table->string("kode");
            $table->text("deskripsi");
            $table->foreignId("mata_kuliah_id")->constrained('mata_kuliahs')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['mata_kuliah_id', 'kode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tujuan_belajars');
    }
};
