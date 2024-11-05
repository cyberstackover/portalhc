<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatPelatihan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_pelatihan', function (Blueprint $table) {
            $table->id();           
            $table->integer('id_talenta');
            $table->string('pengembangan_kompetensi');
            $table->integer('lama_hari');
            $table->string('kota');
            $table->string('penyelenggara');
            $table->string('nomor_sertifikasi');
            $table->string('jenis_diklat');
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
        Schema::dropIfExists('riwayat_pelatihan');
    }
}
