<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAuthorAndIsEditorToClusterUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cluster_user', function (Blueprint $table) {
            $table->boolean('is_author')->default(0);
            $table->boolean('is_editor')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cluster_user', function (Blueprint $table) {
            $table->dropColumn(['is_author', 'is_editor']);
        });
    }
}
