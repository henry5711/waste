<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Operation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_sucursal')->nullable();
            $table->string('coordenada',400);
            $table->dateTime('fecha_ope');
            $table->string('obs',200)->nullable();
            $table->enum('tipo',['web','app']);
            $table->float('peso')->nullable();
            $table->enum('status',['Creada','Terminada','Cliente NR','Pendiente','En ruta']);
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
        Schema::dropIfExists('operation');
    }
}
