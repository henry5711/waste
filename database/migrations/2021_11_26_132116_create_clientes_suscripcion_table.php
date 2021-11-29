<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesSuscripcionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_suscripcion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_suscripcion',false,true);
            $table->bigInteger('id_client',false,true);
            $table->string('nombre');
            $table->string('correo_ruc');
            

            $table->foreign('id_suscripcion')->references('id')->on('suscripciones')->onDelete('RESTRICT');
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
        Schema::dropIfExists('clientes_suscripcion');
    }
}
