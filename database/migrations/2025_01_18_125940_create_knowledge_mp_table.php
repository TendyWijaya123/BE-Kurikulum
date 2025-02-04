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
        Schema::create('knowledge_mp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mp_id')->constrained('materi_pembelajaran')->onDelete('cascade');
            $table->string('code_knowledge_dimension');

            $table->foreign('code_knowledge_dimension')->references('code')->on('knowledge_dimensions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_mp');
    }
};
