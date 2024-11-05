<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAssessmentNilaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assessment_nilai', function(Blueprint $table){
            $table->string('spa_report')->default(NULL)->nullable();
            $table->text('area_kekuatan')->default(NULL)->nullable();
            $table->text('area_pengembangan')->default(NULL)->nullable();
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
