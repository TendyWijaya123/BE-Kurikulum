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
        Schema::create('isntrumen_penilaian', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->enum('kategori', ["Project","Quiz", "Case Study",  "Tugas", "ETS", "EAS"]);
            $table->foreignId('tujuan_belajar_id')->nullable()->constrained('tujuan_belajar_rps')->onDelete('set null');
            $table->foreignId('cpl_id')->nullable()->constrained('cpls')->onDelete('set null');
            $table->integer('bobot_penilaian')->nullable()->default(0);
            $table->foreignId('rps_id')->nullable()->constrained('rps_matakuliah')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('isntrumen_penilaian');
    }
};
