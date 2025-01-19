<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cpl_p', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpl_id')->constrained('cpls')->onDelete('cascade');
            $table->foreignId('p_id')->constrained('pengetahuans')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['cpl_id', 'p_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpl_p');
    }
};