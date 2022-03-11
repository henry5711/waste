<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteSucursalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('sucursal_suscripcion');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::create('sucursal_suscripcion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sucursal_id',false,true);
            $table->bigInteger('suscripcion_id',false,true);
            $table->timestamps();
        });
    }
}
