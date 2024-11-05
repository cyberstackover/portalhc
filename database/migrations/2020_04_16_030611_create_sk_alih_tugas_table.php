<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkAlihTugasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sk_alih_tugas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_rincian_sk');
            //$table->foreign('id_rincian_sk')->references('id')->on('rincian_sk');
            $table->integer('id_organ_perusahaan');
            //$table->foreign('id_organ_perusahaan')->references('id')->on('organ_perusahaan');
            $table->integer('id_struktur_organ');
            //$table->foreign('id_struktur_organ')->references('id')->on('struktur_organ');
            $table->integer('id_kategori_mobility');
            //$table->foreign('id_kategori_mobility')->references('id')->on('kategori_mobility');
            $table->char('keterangan', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sk_alih_tugas');
    }
}
