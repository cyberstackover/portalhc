<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlasanPemberhentianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alasan_pemberhentian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kategori_pemberhentian');
            $table->foreign('id_kategori_pemberhentian')->references('id')->on('kategori_pemberhentian');
            $table->char('keterangan', 100)->nullable();
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
        Schema::dropIfExists('alasan_pemberhentian');
    }
}
