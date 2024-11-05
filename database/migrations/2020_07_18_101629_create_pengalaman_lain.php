<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengalamanLain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengalaman_lain', function (Blueprint $table) {
            $table->id();
            $table->integer('id_talenta');
            $table->string('acara');
            $table->string('penyelenggara');
            $table->integer('periode');
            $table->string('lokasi');
            $table->string('peserta');
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
        Schema::dropIfExists('pengalaman_lain');
    }
}
