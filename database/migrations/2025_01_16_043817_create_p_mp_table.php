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
        Schema::create('p_mp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('p_id')->constrained('pengetahuans')->onDelete('cascade');
            $table->foreignId('mp_id')->constrained('materi_pembelajaran')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['p_id', 'mp_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_mp');
    }
};
