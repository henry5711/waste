<?php

namespace App\Http\Controllers\suscripciones;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\suscripciones;
use App\Rules\CaseSensitive;
use App\Rules\CaseSensitiveId;
use App\Services\suscripciones\suscripcionesService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

/** @property suscripcionesService $service */
class suscripcionesController extends CrudController
{
    public function __construct(suscripcionesService $service)
    {
        parent::__construct($service);
    }

    public function _store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'numero' => [new CaseSensitive('suscripciones')]
            ],
            [

            ],
            [
                'numero' => 'numero de suscripcion'
            ]
            );
        if ($validator->fails()) {
            return response()->json(["error"=>true,"message"=>$this->parseMessageBag($validator->getMessageBag())],422);
        }
        return parent::_store($request);
    }

    public function _update($id, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
               'numero' => [new CaseSensitiveId('suscripciones',$id)]
            ],
            [

            ]
            );
        if ($validator->fails()) {
            return response()->json(["error"=>true,"message"=>$this->parseMessageBag($validator->getMessageBag())],422);
        }
        return parent::_update($id,$request);
    }
    public function facturar()
    {
        $cobrar=suscripciones::where('sta','Activa')->get();
       // $cobrar=json_encode($cobrar);
        //$c=["list"=>$cobrar];
        $json = [
            'list' =>$cobrar
        ];
        $client=new Client();
        $endpoint = env('BILLING_API').'factura/suscripcion';
        
        $res=$client->request('POST',$endpoint,['json' => $json]);
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

    public function usuariosus(Request $request)
    {
        $u=$request->usu;
        $sus=suscripciones::where('id_sus',$u)->get();
        return ["list"=>$sus,"total"=>count($sus)];
    }

    public function verDetalle($id_suscripcion){
        return $this->service->verDetalle($id_suscripcion);
    }

    public function buscarCliente($id_client){
        
        return $this->service->buscarCliente($id_client);
    }
    
}