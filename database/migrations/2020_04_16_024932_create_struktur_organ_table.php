<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStrukturOrganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('struktur_organ', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->integer('level');
            $table->integer('urut');
            $table->integer('id_perusahaan');
            //$table->foreign('id_perusahaan')->references('id')->on('perusahaan');
            $table->integer('id_jenis_jabatan');
            //$table->foreign('id_jenis_jabatan')->references('id')->on('jenis_jabatan');
            $table->char('nomenklatur_jabatan', 300)->nullable();
            $table->double('prosentase_gaji', 8, 2)->nullable();
            $table->boolean('aktif')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('struktur_organ');
    }
}
