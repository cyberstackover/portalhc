<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatPekerjaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->integer('id_talenta');
            //$table->foreign('id_talenta')->references('id')->on('talenta');
            $table->string('jabatan', 500)->nullable();
            $table->string('uraian_singkat', 500)->nullable();
            $table->string('capaian', 500)->nullable();
            $table->date('tanggal_awal')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->boolean('masih_bekerja')->nullable();
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
        Schema::dropIfExists('riwayat_pekerjaan');
    }
}
