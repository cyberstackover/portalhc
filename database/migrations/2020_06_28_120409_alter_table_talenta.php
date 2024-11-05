<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTalenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talenta', function (Blueprint $table) {
            $table->string('gelar')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('talenta', 'gelar'))
        {
            Schema::table('talenta', function (Blueprint $table)
            {
                $table->dropColumn('gelar');
            });
        }

        if (Schema::hasColumn('talenta', 'tempat_lahir'))
        {
            Schema::table('talenta', function (Blueprint $table)
            {
                $table->dropColumn('tempat_lahir');
            });
        }

        if (Schema::hasColumn('talenta', 'tanggal_lahir'))
        {
            Schema::table('talenta', function (Blueprint $table)
            {
                $table->dropColumn('tanggal_lahir');
            });
        }
    }
}
