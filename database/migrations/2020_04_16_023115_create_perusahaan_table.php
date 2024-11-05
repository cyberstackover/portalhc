<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerusahaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id();
            $table->char('id_angka', 100)->nullable();
            $table->char('id_huruf', 100)->nullable();
            $table->char('nama_lengkap', 500)->nullable();
            $table->char('nama_singkat', 300)->nullable();
            $table->integer('jenis_akun_id')->nullable();
            $table->integer('jenis_akun_sehat')->nullable();
            $table->integer('urut')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perusahaan');
    }
}
