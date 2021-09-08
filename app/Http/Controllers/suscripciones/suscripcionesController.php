<?php

namespace App\Http\Controllers\suscripciones;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\suscripciones;
use App\Services\suscripciones\suscripcionesService;
/** @property suscripcionesService $service */
class suscripcionesController extends CrudController
{
    public function __construct(suscripcionesService $service)
    {
        parent::__construct($service);
    }

    public function facturar()
    {
        $cobrar=suscripciones::where('sta','Activa')->get();
        dd($cobrar);
    }


    public function editarproxifecha(Request $request)
    {
        foreach($request->list as $s)
        {
            $susc = $s['id'];
            $up=suscripciones::where('id',$susc)->first();
            $up->prox_cob=$s['cobro'];
            $up->save();
        }

        return response()->json('Factiraciones procesadas');
    }
}