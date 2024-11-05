<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetAsalInstansiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_asal_instansi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jenis_asal_instansi');
            $table->foreign('id_jenis_asal_instansi')->references('id')->on('jenis_asal_instansi');
            $table->integer('jumlah_minimal')->nullable();
            $table->integer('jumlah_maksimal')->nullable();
            $table->char('keterangan', 100)->nullable();
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
        Schema::dropIfExists('target_asal_instansi');
    }
}
