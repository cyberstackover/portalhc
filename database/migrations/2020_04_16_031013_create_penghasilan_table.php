<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenghasilanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penghasilan', function (Blueprint $table) {
            $table->id();
            $table->integer('id_perusahaan');
            //$table->foreign('id_perusahaan')->references('id')->on('perusahaan');
            $table->integer('id_struktur_organ');
            //$table->foreign('id_struktur_organ')->references('id')->on('struktur_organ');
            $table->year('tahun')->nullable();
            $table->bigInteger('gaji_pokok')->nullable();
            $table->bigInteger('tantiem')->nullable();
            $table->bigInteger('tunjangan')->nullable();
            $table->bigInteger('takehomepay')->nullable();
            $table->string('keterangan', 350)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penghasilan');
    }
}
