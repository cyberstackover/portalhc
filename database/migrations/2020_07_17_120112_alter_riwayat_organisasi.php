<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRiwayatOrganisasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('riwayat_organisasi', function (Blueprint $table) {
            $table->boolean('formal_flag')->default(true);
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('riwayat_organisasi', 'formal_flag'))
        {
            Schema::table('riwayat_organisasi', function (Blueprint $table)
            {
                $table->dropColumn('formal_flag');
            });
        }  
    }
}
