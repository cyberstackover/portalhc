<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKontenCvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('konten_cv', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 500)->nullable();
            $table->string('tablename', 500)->nullable();
            $table->boolean('aktif')->nullable();
            $table->string('keterangan', 500)->nullable();
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
        Schema::dropIfExists('konten_cv');
    }
}
