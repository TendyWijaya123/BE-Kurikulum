<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prasyarat_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->foreignId('prasyarat_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['mata_kuliah_id', 'prasyarat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prasyarat_mata_kuliah');
    }
};
