<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIstriSuamiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('istri_suami', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_talenta')->nullable();
            //$table->foreign('id_talenta')->references('id')->on('talenta');
            $table->string('nama_pasangan', 500)->nullable();
            $table->string('jenis_kelamin', 100)->nullable();
            $table->string('tempat_lahir', 300)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->date('tanggal_menikah')->nullable();
            $table->string('pekerjaan', 400)->nullable();
            $table->string('keterangan', 300)->nullable();
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
        Schema::dropIfExists('istri_suami');
    }
}
