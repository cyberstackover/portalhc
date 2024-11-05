<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataPenghargaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_penghargaan', function (Blueprint $table) {
            $table->id();
            $table->integer('id_talenta');
            $table->string('jenis_penghargaan');
            $table->string('tingkat');
            $table->string('pemberi_penghargaan');
            $table->integer('tahun');
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
        Schema::dropIfExists('data_penghargaan');
    }
}
