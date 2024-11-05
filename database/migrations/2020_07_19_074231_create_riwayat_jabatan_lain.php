<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatJabatanLain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_jabatan_lain', function (Blueprint $table) {
            $table->id();
            $table->integer('id_talenta');
            $table->string('penugasan');
            $table->text('tupoksi');
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir')->nullable();
            $table->string('instansi');
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
        Schema::dropIfExists('riwayat_jabatan_lain');
    }
}
