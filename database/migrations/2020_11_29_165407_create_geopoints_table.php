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
        // the lat Lon field is created as a geometry data in table, in order to query for
        // STContains, to find all the points within the polygon, which is super fast, if used
        // with spatial indeces in sql
        Schema::create('geopoints', function (Blueprint $table) {
            $table->id();
            //$table->increments('id');
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->point('latLon');
            $table->double('area_sqm')->default(0);
            $table->integer('numpanels')->default(0);
            $table->string('roofclass')->default(0);
            $table->double('annual_gen_GBP')->default(0);
            $table->double('annual_gen_kWh')->default(0);
            $table->integer('breakeven_years')->default(0);
            $table->double('system_cost_GBP')->default(0);
            $table->double('lifetime_gen_GBP')->default(0);
            $table->double('annual_co2_saved_kg')->default(0);
            $table->double('system_capacity_kWp')->default(0);
            $table->double('lifetime_co2_saved_kg')->default(0);
            $table->double('lifecycle_co2_emissions_kg')->default(0);
            $table->double('lifetime_return_on_investment_percent')->default(0);
            $table->json('lats')->nullabe();
            $table->json('lons')->nullable();
            $table->json('yearly_co2_saved_kg')->nullable();
            $table->json('monthly_gen_export_value_GBP')->nullable();
            $table->json('monthly_gen_saving_value_GBP')->nullable();
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
