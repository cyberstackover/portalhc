<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTalentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talenta', function (Blueprint $table) {
            $table->integer('id_agama')->nullable();
            $table->text('alamat')->nullable();
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('talenta', 'id_agama'))
        {
            Schema::table('talenta', function (Blueprint $table)
            {
                $table->dropColumn('id_agama');
            });
        }

        if (Schema::hasColumn('talenta', 'alamat'))
        {
            Schema::table('talenta', function (Blueprint $table)
            {
                $table->dropColumn('alamat');
            });
        }
    }
}
