<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RiwayatJabatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('riwayat_jabatan_dirkomwas', function (Blueprint $table){
            $table->integer('bidang_jabatan_id')->nullable();
        });
        Schema::table('riwayat_jabatan_lain', function (Blueprint $table){
            $table->integer('bidang_jabatan_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
