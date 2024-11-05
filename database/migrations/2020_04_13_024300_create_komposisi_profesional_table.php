<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomposisiProfesionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komposisi_profesional', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kelas_bumn');
            $table->foreign('id_kelas_bumn')->references('id')->on('kelas_bumn');
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
        Schema::dropIfExists('komposisi_profesional');
    }
}
