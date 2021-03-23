<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToGeopointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('geopoints', function (Blueprint $table) {
            $table->string('existingsolar')->default("N");
            $table->json('yearly_gen_captive_kWh')->nullable();
            $table->json('yearly_gen_export_kWh')->nullable();
            $table->json('monthly_gen_export_kWh')->nullable();
            $table->json('monthly_gen_captive_kWh')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('geopoints', function (Blueprint $table) {
            $table->dropColumn("monthly_gen_export_kWh");
            $table->dropColumn("monthly_gen_captive_kWh");
            $table->dropColumn("existingsolar");
            $table->dropColumn("yearly_gen_captive_kWh");
            $table->dropColumn("yearly_gen_export_kWh");

        });
    }
}
