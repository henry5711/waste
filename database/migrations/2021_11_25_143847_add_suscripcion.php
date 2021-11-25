<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuscripcion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suscripciones', function (Blueprint $table) {
            $table->dropColumn('id_sus');
            $table->integer('id_client')->nullable();
            $table->string('periodo')->nullable();
            $table->string('obs',500)->nullable();
            $table->float('base_ip')->nullable();
            $table->float('impuesto')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suscripciones', function (Blueprint $table) {
            //
        });
    }
}
