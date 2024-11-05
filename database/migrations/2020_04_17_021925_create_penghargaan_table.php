<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenghargaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penghargaan', function (Blueprint $table) {
            $table->id();
            $table->integer('id_talenta');
            //$table->foreign('id_talenta')->references('id')->on('talenta');
            $table->integer('id_tingkat_penghargaan');
            //$table->foreign('id_tingkat_penghargaan')->references('id')->on('tingkat_penghargaan');
            $table->string('nama_penghargaan', 500)->nullable();
            $table->string('pemberi_penghargaan', 500)->nullable();
            $table->year('tahun');
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
        Schema::dropIfExists('penghargaan');
    }
}
