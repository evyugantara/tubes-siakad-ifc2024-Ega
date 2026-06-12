<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('krs', function (Blueprint $table) {
            $table->increments('id');
            $table->char('npm', 10);
            $table->char('kode_matakuliah', 8);
            $table->timestamps();

            $table->foreign('npm')
                  ->references('npm')
                  ->on('mahasiswa')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('kode_matakuliah')
                  ->references('kode_matakuliah')
                  ->on('matakuliah')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
