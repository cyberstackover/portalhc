<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LogSyncPerusahaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('perusahaan_log_sync', function (Blueprint $table){
            $table->id();
            $table->integer('perusahaan_id')->nullable();
            $table->string('id_angka')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('nama_singkat')->nullable();
            $table->integer('jenis_akun_id')->nullable();
            $table->integer('jenis_akun_sehat')->nullable();
            $table->integer('urut')->nullable();
            $table->string('logo')->nullable();
            $table->string('jenis_perusahaan')->nullable();
            $table->string('kepemilikan')->nullable();
            $table->text('bidang_usaha')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->string('url')->nullable();
            $table->integer('induk')->nullable();
            $table->integer('level')->nullable();
            $table->integer('kelas')->nullable();
            $table->integer('id_jenis_perusahaan')->nullable();
            $table->integer('id_klaster')->nullable();
            $table->integer('wamen')->nullable();
            $table->boolean('is_active')->default(false)->nullable();
            $table->integer('created_by')->nullable();
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
        //
    }
}
