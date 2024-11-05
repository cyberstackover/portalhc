<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkPenetapanPltTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sk_penetapan_plt', function (Blueprint $table) {
            $table->id();
            $table->integer('id_rincian_sk');
            //$table->foreign('id_rincian_sk')->references('id')->on('rincian_sk');
            $table->integer('id_organ_perusahaan');
            //$table->foreign('id_organ_perusahaan')->references('id')->on('organ_perusahaan');
            $table->integer('id_struktur_organ');
            //$table->foreign('id_struktur_organ')->references('id')->on('struktur_organ');
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
        Schema::dropIfExists('sk_penetapan_plt');
    }
}
