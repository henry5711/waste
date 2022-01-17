<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Rutas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cho_id');
            $table->string('cho_name');
            $table->date('fec_ruta');
            $table->float('peso_total')->nullable();
            $table->float('peso_recibio')->nullable();
            $table->enum('status',['CREADA','EN PROCESO','TERMINADA','NO CUMPLIDA']);
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
        Schema::dropIfExists('rutas');
    }
}
