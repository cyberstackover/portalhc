<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerusahaanStatusHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perusahaan_status_history', function (Blueprint $table) {
            $table->id();
            $table->integer('perusahaan_id')->nullable();
            $table->integer('status_perusahaan_id')->nullable();
            $table->date('tmt_awal');
            $table->date('tmt_akhir');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perusahaan_status_history');
    }
}
