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
        Schema::create('sksus', function (Blueprint $table) {
            $table->id();
            $table->string('profil_lulusan');
            $table->string('kualifikasi');
            $table->enum('kategori', ['Siap Kerja', 'Siap Usaha']);
            $table->foreignId('kurikulum_id')->constrained('kurikulums')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sksus');
    }
};
