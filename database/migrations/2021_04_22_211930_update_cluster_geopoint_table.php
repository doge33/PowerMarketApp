<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClusterGeopointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cluster_geopoint', function (Blueprint $table) {
            $table->float('captive_use', 5, 4)->default(0.8);
            $table->float('export_tariff', 5, 4)->default(0.055);
            $table->float('domestic_tariff', 5, 4)->default(0.146);
            $table->float('commercial_tariff', 5, 4)->default(0.12);
            $table->decimal('system_cost')->default(6000);
            $table->decimal('system_size')->default(5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cluster_geopoint', function(Blueprint $table){
            $table->dropColumn(['captive_use', 'export_tariff', 'domestic_tariff', 'commercial_tariff', 'system_cost', 'system_size']);
        });
    }
}
