<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRuta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rutas', function (Blueprint $table) {
            $table->string('pt_ini',400)->nullable();
            $table->string('pt_fin',400)->nullable();
            $table->string('cod_rut')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rutas', function (Blueprint $table) {
            //
        });
    }
}
