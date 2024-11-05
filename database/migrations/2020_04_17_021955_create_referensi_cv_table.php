<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferensiCvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referensi_cv', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_talenta')->nullable();
            //$table->foreign('id_talenta')->references('id')->on('talenta');
            $table->string('nama', 500)->nullable();
            $table->string('perusahaan', 100)->nullable();
            $table->string('jabatan', 300)->nullable();
            $table->string('peserta', 400)->nullable();
            $table->string('nomor_handphone', 300)->nullable();
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
        Schema::dropIfExists('referensi_cv');
    }
}
