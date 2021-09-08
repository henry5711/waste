<?php

namespace App\Http\Controllers\suscripciones;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\suscripciones;
use App\Services\suscripciones\suscripcionesService;
use GuzzleHttp\Client;

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
       // $cobrar=json_encode($cobrar);
        //$c=["list"=>$cobrar];
        $json = [
            'list' =>$cobrar
        ];
        return $json;
        $client=new Client();
        $res=$client->request('POST','https://qarubick2billing.zippyttech.com/api/factura/suscripcion',['json' => $json]);
        return response()->json('las suscripciones estan siendo procesadas');
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

        return response()->json('Fechas editadas');
    }
}