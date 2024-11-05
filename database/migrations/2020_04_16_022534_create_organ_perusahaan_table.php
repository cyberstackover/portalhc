<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganPerusahaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organ_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->integer('id_struktur_organ');
            //$table->foreign('id_struktur_organ')->references('id')->on('struktur_organ');
            $table->integer('id_talenta');
            //$table->foreign('id_talenta')->references('id')->on('talenta');
            $table->integer('id_surat_keputusan');
            //$table->foreign('id_surat_keputusan')->references('id')->on('surat_keputusan');
            $table->date('tanggal_awal')->nullable();
            $table->date('tanggal_akan_berakhir')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->char('plt', 300)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organ_perusahaan');
    }
}
