<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStrukturBidangJabatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('struktur_bidang_jabatan', function (Blueprint $table) {
            $table->id();
            $table->integer('id_struktur_organ');
            //$table->foreign('id_struktur_organ')->references('id')->on('struktur_organ');
            $table->integer('id_bidang_jabatan');
            $table->foreign('id_bidang_jabatan')->references('id')->on('bidang_jabatan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('struktur_bidang_jabatan');
    }
}
