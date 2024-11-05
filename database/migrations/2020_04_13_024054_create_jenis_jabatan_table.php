<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisJabatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_jabatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_grup_jabatan');
            $table->foreign('id_grup_jabatan')->references('id')->on('grup_jabatan');
            $table->char('nama', 100)->nullable();
            $table->float('prosentase_gaji', 8, 2)->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('id_jns_jab_pengali')->nullable();
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
        Schema::dropIfExists('jenis_jabatan');
    }
}
