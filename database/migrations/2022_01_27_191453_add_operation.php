<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOperation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operation', function (Blueprint $table) {
            $table->dateTime('fecha')->nullable();
            $table->string('tlf')->nullable();
            $table->string('ref',500)->nullable();
            $table->enum('usu/cli',['usuario','cliente'])->nullable();
            $table->integer('ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operation', function (Blueprint $table) {
            //
        });
    }
}
