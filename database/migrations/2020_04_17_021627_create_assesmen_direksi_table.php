<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssesmenDireksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assesmen_direksi', function (Blueprint $table) {
            $table->id();
            $table->integer('id_talenta');
            //$table->foreign('id_talenta')->references('id')->on('talenta');
            $table->integer('nilai_asesmen_global');
            $table->integer('nilai_asesmen_domestik');
            $table->string('filename_asesmen', 500)->nullable();
            $table->string('filename_ukk', 500)->nullable();
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
        Schema::dropIfExists('assesmen_direksi');
    }
}
