<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->increments('id');
            $table->char('kode_matakuliah', 8);
            $table->char('nidn', 10);
            $table->char('kelas', 1);
            $table->string('hari', 10);
            $table->timestamp('jam')->nullable();
            $table->timestamps();

            $table->foreign('kode_matakuliah')
                  ->references('kode_matakuliah')
                  ->on('matakuliah')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('nidn')
                  ->references('nidn')
                  ->on('dosen')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
