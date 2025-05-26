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
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 6)->unique();
            $table->string('nip', 18)->unique();
            $table->string('nama', 50);
            $table->string('email', 50);
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->boolean('is_active')->default(true);
            $table->foreignId('jurusan_id')->constrained('jurusans')->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
