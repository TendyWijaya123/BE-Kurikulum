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
        Schema::create('ka_bp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kemampuan_akhir_id')->constrained('kemampuan_akhirs')->onDelete('cascade');
            $table->foreignId('bentuk_pembelajaran_id')->constrained('bentuk_pembelajarans')->onDelete('cascade');
            $table->timestamps();

            // You can add a unique constraint to prevent duplicate relationships
            $table->unique(['kemampuan_akhir_id', 'bentuk_pembelajaran_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ka_bp');
    }
};
