<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkPengangkatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sk_pengangkatan', function (Blueprint $table) {
            $table->id();
            $table->integer('id_rincian_sk');
            //$table->foreign('id_rincian_sk')->references('id')->on('rincian_sk');
            $table->integer('id_struktur_organ');
            //$table->foreign('id_struktur_organ')->references('id')->on('struktur_organ');
            $table->integer('id_periode_jabatan');
            //$table->foreign('id_periode_jabatan')->references('id')->on('periode_jabatan');
            $table->integer('id_talenta');
            //$table->foreign('id_talenta')->references('id')->on('talenta');
            $table->integer('id_kategori_mobility');
            //$table->foreign('id_kategori_mobility')->references('id')->on('kategori_mobility');
            $table->integer('id_jenis_mobility');
            //$table->foreign('id_jenis_mobility')->references('id')->on('jenis_mobility');
            $table->integer('id_rekomendasi');
            //$table->foreign('id_rekomendasi')->references('id')->on('rekomendasi');
            $table->date('tanggal_awal_menjabat')->nullable();
            $table->date('tanggal_akhir_menjabat')->nullable();
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
        Schema::dropIfExists('sk_pengangkatan');
    }
}
