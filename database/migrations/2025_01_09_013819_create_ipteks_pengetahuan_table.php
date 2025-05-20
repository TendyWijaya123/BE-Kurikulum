<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpteksPengetahuanTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ilmu_pengetahuans', function (Blueprint $table) {
            $table->id();
            $table->text('deskripsi');
            $table->string('link_sumber')->nullable();
            $table->foreignId('kurikulum_id')->constrained('kurikulums')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('ilmu_pengetahuans');
    }
}
