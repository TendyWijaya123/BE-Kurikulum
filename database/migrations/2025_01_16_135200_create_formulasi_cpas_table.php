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
        Schema::create('formulasi_cpas', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->text('deskripsi');
            $table->enum('kategori', ['C', 'P', 'A']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulasi_cpas');
    }
};
