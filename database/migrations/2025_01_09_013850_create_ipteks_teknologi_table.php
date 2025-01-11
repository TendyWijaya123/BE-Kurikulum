<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpteksTeknologiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ipteks_teknologi', function (Blueprint $table) {
            $table->id();
            $table->string('teknologi');
            $table->foreignId('kurikulum_id')->constrained('kurikulums')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('ipteks_teknologi');
    }
}
