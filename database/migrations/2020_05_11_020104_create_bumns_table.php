<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bumns', function (Blueprint $table) {
            $table->id();
            $table->string('id_angka')->nullable();
            $table->string('id_huruf')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('nama_singkat')->nullable();
            $table->string('logo')->nullable();
            $table->string('jenis_perusahaan')->nullable();
            $table->string('kepemilikan')->nullable();
            $table->text('bidang_usaha')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->string('url')->nullable();
            $table->integer('induk')->nullable();
            $table->integer('level')->nullable();
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
        Schema::dropIfExists('bumns');
    }
}
