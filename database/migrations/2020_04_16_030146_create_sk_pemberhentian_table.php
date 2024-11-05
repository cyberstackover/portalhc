<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkPemberhentianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sk_pemberhentian', function (Blueprint $table) {
            $table->id();
            $table->integer('id_rincian_sk');
            //$table->foreign('id_rincian_sk')->references('id')->on('rincian_sk');
            $table->integer('id_talenta');
            //$table->foreign('id_talenta')->references('id')->on('talenta');
            $table->integer('id_struktur_organ');
            //$table->foreign('id_struktur_organ')->references('id')->on('struktur_organ');
            $table->integer('id_alasan_pemberhentian');
            //$table->foreign('id_alasan_pemberhentian')->references('id')->on('alasan_pemberhentian');
            $table->date('tanggal_akhir_menjabat')->nullable();
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
        Schema::dropIfExists('sk_pemberhentian');
    }
}
