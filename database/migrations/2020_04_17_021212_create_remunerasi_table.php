<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemunerasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remunerasi', function (Blueprint $table) {
            $table->id();
            $table->integer('id_bumn');
            $table->integer('tahun');
            $table->integer('id_jenis_jabatan');
            $table->integer('id_faktor_penghasilan');
            $table->bigInteger('jumlah');
            $table->bigInteger('jumlah_default');
            $table->integer('id_mata_uang')->nullable();
            $table->integer('kurs')->nullable();
            $table->date('tgl_kurs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remunerasi');
    }
}
