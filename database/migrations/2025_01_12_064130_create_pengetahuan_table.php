<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pengetahuans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengetahuan');
            $table->text('deskripsi');
            $table->unsignedBigInteger('kurikulum_id');
            $table->timestamps();

            $table->foreign('kurikulum_id')->references('id')->on('kurikulums')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengetahuans');
    }
};
