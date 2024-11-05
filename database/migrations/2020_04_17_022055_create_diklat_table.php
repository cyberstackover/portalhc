<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiklatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diklat', function (Blueprint $table) {
            $table->id();
            $table->integer('id_talenta');
            //$table->foreign('id_talenta')->references('id')->on('talenta');
            $table->integer('id_jenis_diklat');
            //$table->foreign('id_jenis_diklat')->references('id')->on('jenis_diklat');
            $table->string('nama_lembaga', 350)->nullable();
            $table->string('universitas', 350)->nullable();
            $table->date('tanggal_awal')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->string('nomor_sertifikat', 350)->nullable();
            $table->string('kota', 350)->nullable();
            $table->string('negara', 350)->nullable();
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
        Schema::dropIfExists('diklat');
    }
}
