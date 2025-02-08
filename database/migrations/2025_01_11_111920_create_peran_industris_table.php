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
        Schema::create('peran_industris', function (Blueprint $table) {
            $table->id();
            $table->string("jabatan");
            $table->text("deskripsi");
            $table->foreignId("kurikulum_id")->constrained('kurikulums');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peran_industris');
    }
};
