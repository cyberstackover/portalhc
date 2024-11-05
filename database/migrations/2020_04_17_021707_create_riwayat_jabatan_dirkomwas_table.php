<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatJabatanDirkomwasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_jabatan_dirkomwas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_talenta');
            //$table->foreign('id_talenta')->references('id')->on('talenta');
            $table->string('nama_perusahaan', 500)->nullable();
            $table->string('jabatan', 500)->nullable();
            $table->string('tupoksi', 500)->nullable();
            $table->date('tanggal_awal')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->boolean('masih_bekerja');
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
        Schema::dropIfExists('riwayat_jabatan_dirkomwas');
    }
}
