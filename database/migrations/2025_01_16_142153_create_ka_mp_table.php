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
        Schema::create('ka_mp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metode_pembelajaran_id')->constrained('metode_pembelajarans')->onDelete('cascade');
            $table->foreignId('kemampuan_akhir_id')->constrained('kemampuan_akhirs')->onDelete('cascade');
            $table->timestamps();

            // You can also add unique constraint if needed
            $table->unique(['metode_pembelajaran_id', 'kemampuan_akhir_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ka_mp');
    }
};
