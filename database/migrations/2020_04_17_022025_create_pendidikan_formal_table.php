<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendidikanFormalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendidikan_formal', function (Blueprint $table) {
            $table->id();
            $table->integer('id_talenta');
            //$table->foreign('id_talenta')->references('id')->on('talenta');
            $table->integer('id_jenjang_pendidikan');
            //$table->foreign('id_jenjang_pendidikan')->references('id')->on('jenjang_pendidikan');
            $table->string('jurusan', 350)->nullable();
            $table->string('universitas', 350)->nullable();
            $table->year('tahun_lulus');
            $table->string('penghargaan', 350)->nullable();
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
        Schema::dropIfExists('pendidikan_formal');
    }
}
