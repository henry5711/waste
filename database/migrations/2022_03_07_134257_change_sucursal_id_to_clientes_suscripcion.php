<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSucursalIdToClientesSuscripcion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes_suscripcion', function (Blueprint $table) {
            //
            $table->dropColumn('sucursal_id');
        });
        Schema::table('clientes_suscripcion', function (Blueprint $table) {
            //
            $table->bigInteger('id_sucursal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes_suscripcion', function (Blueprint $table) {
            //
            $table->dropColumn('id_sucursal');
        });
        Schema::table('clientes_suscripcion', function (Blueprint $table) {
            //
            $table->bigInteger('sucursal_id')->nullable();
        });
    }
}
