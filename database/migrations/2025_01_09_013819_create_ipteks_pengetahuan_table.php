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
        Schema::create('ipteks_pengetahuan', function (Blueprint $table) {
            $table->id();
            $table->string('ilmu_pengetahuan');
            $table->foreignId('kurikulum_id')->constrained('kurikulums')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('ipteks_pengetahuan');
    }
}
