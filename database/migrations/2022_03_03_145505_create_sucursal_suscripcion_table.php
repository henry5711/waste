<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSucursalSuscripcionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursal_suscripcion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sucursal_id')->comment('foránea: API Client');
            $table->bigInteger('suscripcion_id')->comment('foránea: tabla suscripciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sucursal_suscripcion');
    }
}
