<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            'id'=> 1,
           'name'=>'Por Confirmar',
            'code'=> 1
        ]);

        DB::table('statuses')->insert([
            'id'=> 2,
           'name'=>'Confirmado',
            'code'=> 2
        ]);
        DB::table('statuses')->insert([
            'id'=> 3,
           'name'=>'Anulado',
            'code'=> 3
        ]);
    }
}
