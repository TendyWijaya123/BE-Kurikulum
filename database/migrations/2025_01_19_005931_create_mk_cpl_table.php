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
        Schema::create('mk_cpl', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->foreignId('cpl_id')->constrained('cpls')->onDelete('cascade');
            $table->foreignId('mk_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['cpl_id', 'mk_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mk_cpl');
    }
};
