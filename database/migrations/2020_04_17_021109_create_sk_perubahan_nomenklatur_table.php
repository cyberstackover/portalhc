<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkPerubahanNomenklaturTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sk_perubahan_nomenklatur', function (Blueprint $table) {
            $table->id();
            $table->integer('id_rincian_sk');
            //$table->foreign('id_rincian_sk')->references('id')->on('rincian_sk');
            $table->integer('id_struktur_organ');
            //$table->foreign('id_struktur_organ')->references('id')->on('struktur_organ');
            $table->integer('id_kategori_mobility');
            //$table->foreign('id_kategori_mobility')->references('id')->on('kategori_mobility');
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
        Schema::dropIfExists('sk_perubahan_nomenklatur');
    }
}
