<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talenta', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 400)->nullable();
            $table->string('jenis_kelamin', 100)->nullable();
            $table->string('nik', 100)->nullable();
            $table->string('npwp', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('nomor_hp', 100)->nullable();
            $table->integer('id_asal_instansi')->nullable();
            $table->integer('jabatan_asal_instansi')->nullable();
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
        Schema::dropIfExists('talenta');
    }
}
