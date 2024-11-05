<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilePendukungTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_pendukung', function (Blueprint $table) {
            $table->id();
            $table->integer('id_surat_keputusan');
            //$table->foreign('id_surat_keputusan')->references('id')->on('surat_keputusan');
            $table->integer('id_jenis_sk');
            //$table->foreign('id_jenis_sk')->references('id')->on('jenis_sk');
            $table->char('filename', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_pendukung');
    }
}
