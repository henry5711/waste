<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutomaticOperationsToConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('config', function (Blueprint $table) {
            //
            $table->boolean('automatic_operations')->default(false)->comment('Crea operaciones automáticas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('config', function (Blueprint $table) {
            //
            $table->dropColumn('automatic_operations');
        });
    }
}
