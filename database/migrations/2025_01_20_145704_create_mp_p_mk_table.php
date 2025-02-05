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
        Schema::create('mp_p_mk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mp_p_id')->constrained('p_mp')->onDelete('cascade');
            $table->foreignId('mk_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['mp_p_id', 'mk_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mp_p_mk');
    }
};
