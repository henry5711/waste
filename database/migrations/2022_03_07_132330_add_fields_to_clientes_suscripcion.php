<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToClientesSuscripcion extends Migration
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
            $table->string('nombre_sucursal')->nullable();
            $table->string('coordenada_sucursal')->nullable();
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
            $table->dropColumn([
                'nombre_sucursal',
                'coordenada_sucursal'
            ]);
        });
    }
}
