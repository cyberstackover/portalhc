<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatPendidikan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_pendidikan', function (Blueprint $table) {
            $table->id();            
            $table->integer('id_talenta');
            $table->integer('id_jenjang_pendidikan');
            $table->string('perguruan_tinggi');
            $table->string('penjurusan');
            $table->string('kota');
            $table->string('negara');
            $table->string('penghargaan')->nullable();
            $table->integer('tahun');
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
        Schema::dropIfExists('riwayat_pendidikan');
    }
}
