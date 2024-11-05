<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminatanPosisiDireksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminatan_posisi_direksi', function (Blueprint $table) {
            $table->id();
            $table->integer('id_talenta');
            //$table->foreign('id_talenta')->references('id')->on('talenta');
            $table->integer('id_bidang_jabatan');
            //$table->foreign('id_bidang_jabatan')->references('id')->on('bidang_jabatan');
            $table->string('nomenklatur_baru', 500)->nullable();
            $table->string('keterangan', 500)->nullable();
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
        Schema::dropIfExists('peminatan_posisi_direksi');
    }
}
