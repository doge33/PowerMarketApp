<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGeopointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //add default PRO prarms to each geopoint
        Schema::table('cluster_geopoint', function (Blueprint $table) {
            $table->decimal('captive_use')->default(0.8);
            $table->decimal('export_tariff')->default(5.5);
            $table->decimal('domestic_tariff')->default(14.36);
            $table->decimal('commercial_tariff')->default(12.0);


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
