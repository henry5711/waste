<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsToPlanes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planes', function (Blueprint $table) {
            //
            $table->dropColumn(['condi', 'tipo']);
            $table->integer('id_propietario')->unsigned()->nullable();
            $table->string('propietario')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planes', function (Blueprint $table) {
            //
            $table->dropColumn(['id_propietario', 'propietario']);
            $table->string('condi')->nullable();
            $table->string('tipo')->nullable();
        });
    }
}