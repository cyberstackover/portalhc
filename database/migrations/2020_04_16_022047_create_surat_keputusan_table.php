<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratKeputusanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_keputusan', function (Blueprint $table) {
            $table->id();
            $table->integer('id_perusahaan');
            //$table->foreign('id_perusahaan')->references('id')->on('perusahaan');
            $table->integer('id_grup_jabatan');
            //$table->foreign('id_grup_jabatan')->references('id')->on('grup_jabatan');
            $table->char('nomor', 250)->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->date('tanggal_serah_terima')->nullable();
            $table->char('keterangan', 250)->nullable();
            $table->char('file_name', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_keputusan');
    }
}
