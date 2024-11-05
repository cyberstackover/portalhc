<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerusahaanRelasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perusahaan_relasi', function (Blueprint $table) {
            $table->id();
            $table->integer('perusahaan_id')->nullable();
            $table->integer('perusahaan_induk_id')->nullable();
            $table->date('tmt_awal');
            $table->date('tmt_akhir');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perusahaan_relasi');
    }
}
