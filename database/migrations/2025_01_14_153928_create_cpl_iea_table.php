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
        Schema::create('cpl_iea', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpl_id')->constrained('cpls')->onDelete('cascade');
            $table->foreignId('iea_id')->constrained('iea')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['cpl_id', 'iea_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpl_iea');
    }
};
