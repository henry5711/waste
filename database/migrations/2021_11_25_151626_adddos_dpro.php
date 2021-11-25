<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdddosDpro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detallepro', function (Blueprint $table) {
            $table->dropForeign('detallepro_id_su_foreign');
            $table->dropColumn("id_su");
            $table->float('descuento')->nullable();
            $table->integer('id_susp')->nullable();
            $table->foreign('id_susp')->references('id')->on('suscripciones')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detallepro', function (Blueprint $table) {
            //
        });
    }
}
