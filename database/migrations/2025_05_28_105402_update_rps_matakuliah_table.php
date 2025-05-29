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
        Schema::table('rps_matakuliah', function (Blueprint $table) {  
             $table->dropForeign(['tujuan_belajar_id']);  
             $table->dropColumn('tujuan_belajar_id');
            // Tambah kolom baru sesuai model
            $table->text('kemampuan_akhir')->nullable()->after('mata_kuliah_id');
            $table->foreignId('tujuan_belajar_id')->nullable()->constrained('tujuan_belajar_rps')->onDelete('set null')->after('hasil_belajar');

            // Hapus kolom lama yang digantikan
            $table->dropForeign(['kemampuan_akhir_id']);
           
            // Drop column
            $table->dropColumn('kemampuan_akhir_id');
            $table->dropColumn('modalitas_bentuk_strategi_metodepembelajaran');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rps_matakuliah', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn(['modalitas', 'bentuk', 'strategi', 'metodepembelajaran', 'kemampuan_akhir', 'tujuan_belajar_id']);

            // Tambah kembali kolom lama
            $table->foreignId('kemampuan_akhir')->nullable()->constrained('kemampuan_akhirs')->onDelete('set null')->after('mata_kuliah_id');
            $table->foreignId('tujuan_belajar_id')->nullable()->constrained('tujuan_belajar_rps')->onDelete('set null')->after('hasil_belajar');
            $table->text('modalitas_bentuk_strategi_metodepembelajaran')->nullable()->after('pokok_bahasan');

        });
    }
};
