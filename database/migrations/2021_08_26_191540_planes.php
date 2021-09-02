<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Planes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plan');
            $table->float('precio')->nullable();
            $table->enum('Periodicidad', [ 'Semanal', 'Quincenal', 'Mensual', 'Por Recogida'])->nullable();
            $table->string('condi',700)->nullable();
            $table->string('obs')->nullable();
            $table->string('icon')->nullable();
            $table->enum('tipo',[ 'usuario','cliente'])->default('usuario');
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
        Schema::dropIfExists('planes');
    }
}
