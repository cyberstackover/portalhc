<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisStatusMasaJabatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_status_masa_jabatan', function (Blueprint $table) {
            $table->id();
            $table->char('nama', 100)->nullable();
            $table->integer('jumlah_hari_awal')->nullable();
            $table->integer('jumlah_hari_akhir')->nullable();
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
        Schema::dropIfExists('jenis_status_masa_jabatan');
    }
}
