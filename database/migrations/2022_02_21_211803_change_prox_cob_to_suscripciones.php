<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeProxCobToSuscripciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $base_de_datos = new PDO("pgsql:host=".env('DB_HOST').";port=".env('DB_PORT').";dbname=".env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'));
        $base_de_datos->query('ALTER TABLE suscripciones ALTER COLUMN prox_cob TYPE timestamp');
        
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
            $base_de_datos = new PDO("pgsql:host=".env('DB_HOST').";port=".env('DB_PORT').";dbname=".env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'));
            $base_de_datos->query('ALTER TABLE suscripciones ALTER COLUMN prox_cob TYPE date');
        });
    }
}
