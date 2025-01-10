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
        Schema::create('vmt_jurusans', function (Blueprint $table) {
            $table->id();
            $table->text('visi_jurusan');
            $table->text('visi_keilmuan_prodi');
            $table->foreignId('kurikulum_id')
                ->constrained('kurikulums')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();

            $table->unique('kurikulum_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vmt_jurusans');
    }
};
