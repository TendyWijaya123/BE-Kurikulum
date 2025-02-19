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
        Schema::create('kemampuan_kerja_kkni', function (Blueprint $table) {
            $table->id();
            $table->integer('level');
            $table->text('kemampuan_kerja_kkni');
            $table->string('jenjang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kemampuan_kerja_kkni');
    }
};
