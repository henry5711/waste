<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Detallepro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallepro', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_su');
            $table->string('id_pro');
            $table->string('nom_pro');
            $table->float('precio');
            $table->integer('cantidad');
            $table->float('impuesto');
            $table->float('sub_total');
            $table->timestamps();

            $table->foreign('id_su')->references('id')->on('suscripciones')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detallepro');
    }
}
