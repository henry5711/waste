<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Suscripciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numero')->nullable();
            $table->string('id_sus')->nullable();
            $table->string('correo')->nullable();
            $table->string('nombre')->nullable();
            $table->date('fec_ini')->nullable();
            $table->date('fec_fin')->nullable();
            $table->string('ciclo')->nullable();
            $table->string('sta')->nullable();
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
        Schema::dropIfExists('suscripciones');
    }
}
