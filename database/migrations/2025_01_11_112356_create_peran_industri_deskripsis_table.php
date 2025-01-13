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
        Schema::create('peran_industri_deskripsis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peran_industri_id')->constrained('peran_industris')->onDelete('cascade');
            $table->text('deskripsi_point');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peran_industri_deskripsis');
    }
};
