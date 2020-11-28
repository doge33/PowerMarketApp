<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeopointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geopoints', function (Blueprint $table) {
            $table->id();
            $table->point('latLon');
            $table->double('area_sqm')->default(0);
            $table->integer('numpanels')->default(0);
            $table->string('roofclass')->default(0);
            $table->double('annual_gen_GBP')->default(0);
            $table->double('annual_gen_KWh')->default(0);
            $table->integer('breakeven_years')->default(0);
            $table->double('system_cost_GBP')->default(0);
            $table->double('lifetime_gen_GBP')->default(0);
            $table->double('annual_co2_saved_kg')->default(0);
            $table->double('system_capacity_KWp')->default(0);
            $table->double('lifetime_co2_saved_kg')->default(0);
            $table->double('lifecycle_co2_emissions_kg')->default(0);
            $table->double('lifetime_roi_percent')->default(0);
            $table->timestamps();
            $table->spatialIndex('latLon');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geopoints');
    }
}
