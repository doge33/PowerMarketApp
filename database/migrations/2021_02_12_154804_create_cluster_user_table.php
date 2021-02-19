<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClusterUserTable extends Migration
{
    /**
     * Run migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cluster_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cluster_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('cluster_id')->references('id')->on('clusters')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cluster_user');
    }
}
