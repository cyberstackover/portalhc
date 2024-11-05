<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTalentaInterest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_interest', function (Blueprint $table) {
            $table->id();
            $table->integer('id_talenta');
            $table->text('ekonomi')->nullable();
            $table->text('leadership')->nullable();
            $table->text('sosial')->nullable();
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
        Schema::dropIfExists('cv_interest');
    }
}
