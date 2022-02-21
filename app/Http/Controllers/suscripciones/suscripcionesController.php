<?php

namespace App\Http\Controllers\suscripciones;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Http\Mesh\BillingService;
use App\Models\suscripciones;
use App\Rules\CaseSensitive;
use App\Rules\CaseSensitiveId;
use App\Services\suscripciones\suscripcionesService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/** @property suscripcionesService $service */
class suscripcionesController extends CrudController
{
    public function __construct(suscripcionesService $service)
    {
        parent::__construct($service);
    }

    public function _index(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'estado' => Rule::in(['Activa','Pausada','Cancelada'])
            ],
            [
                'in' => ':attribute debe ser :values'
            ]
            );
        if ($validator->fails()) {
            return response()->json(["error"=>true,"message"=>$this->parseMessageBag($validator->getMessageBag())],422);
        }
        return parent::_index($request);
    }

    public function _store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'numero' => [new CaseSensitive('suscripciones')],
                'clientes' => [ 'required' ],
                'titulo' => [ new CaseSensitive('suscripciones') ]
            ],
            [
                'required' => 'El campo :attribute es requerido'
            ],
            [
                'numero' => 'numero de suscripcion'
            ]
            );
        if ($validator->fails()) {
            return response()->json(["error"=>true,"message"=>$this->parseMessageBag($validator->getMessageBag())[0][0]],422);
        }
        return $this->service->_store($request);
    }

    public function _update($id, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
               'numero' => [new CaseSensitiveId('suscripciones',$id)],
               'clientes' => [ 'required' ],
               'titulo' => [ new CaseSensitiveId('suscripciones',$id) ]
            ],
            [
                'required' => 'El campo :attribute es requerido'
            ]
            );
        if ($validator->fails()) {
            return response()->json(["error"=>true,"message"=>$this->parseMessageBag($validator->getMessageBag())[0][0]],422);
        }
        return $this->service->_update($id,$request);
    }

    public function facturar(Request $request){

        if($request->all == true && count($request->suscripciones) == 0){
            //return 'todas';
            $cobrar = suscripciones::with([
                'Clientes',
                'Productos'
            ])
            ->where('sta','Activa')
            ->has('Clientes','>=')
            ->get();
        }else
        if($request->all == false && count($request->suscripciones) > 0){
            //return 'determinadas';
            $cobrar = suscripciones::with([
                'Clientes',
                'Productos'
            ])
            ->where('sta','Activa')
            ->whereIn('id',$request->suscripciones)
            ->has('Clientes','>=')
            ->get();
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Incorrecta combinación de parámetros'
            ],425);
        }
       
       if( $cobrar->count() == 0 ){
           return response()->json([
               "error" => true,
               "message" => "No hay suscripciones para facturar"
           ],425);
       }
       
        $fecha = Carbon::now()->format('Y-m-d');
        
        
        $json = [
            'list' =>$cobrar
        ];
        
        // return $json;
        $client=new BillingService;
        return $client->generarFacturas($json);
        
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

    public function buscarClienteId($id_client){
        return $this->service->buscarClienteId($id_client);
    }

    public function buscarClienteFiltro($id_suscripcion, Request $request){
        $request['id_suscripcion'] = $id_suscripcion;
        return $this->service->buscarClienteFiltro($request);
    }

    public function generarNumero(){
        $numero = $this->service->generarNumero();
        return response()->json([
            "numero" => $numero
        ],200);
    }

    public function mostrarFacturas(){
        return $this->service->mostrarFacturas();
    }

    public function estado(Request $request){
        
        $validator = Validator::make(
            $request->all(),
            [
                'estado' => Rule::in(['Activa','Pausada','Cancelada','Por Confirmar'])
            ],
            [
                'in' => ':attribute debe ser :values'
            ]);
        if ($validator->fails()) {
            $err = $this->parseMessageBag($validator->getMessageBag());
            return response()->json(
                [
                "error"=>true,
                "message"=>$err[0][0]
                ],422);
        }

        $id_suscripcion = $request->id_suscripcion;
        $estado = $request->estado;
        return $this->service->estado($id_suscripcion,$estado);
    }
    
    public function Filtro(Request $request){
        return $this->service->Filtro($request);
    }

    public function calcularFacturas(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'all' => 'nullable|boolean|required_without:suscripciones',
                'suscripciones' => 'nullable|array|required_without:all'
            ],
            [
                'boolean' => 'El campo :attribute debe ser True o False',
                'array' => 'El campo :attribute debe ser un array de ids',
                'required_without' => 'El campo :attribute es requerido si no envía el campo :values'
            ]
        );

        if($validator->fails()){
            $error = [
                'error' => true,
                'message' => $validator->getMessageBag()->all()
            ];
            
            return response()->json($error,422);
        }

        if( ( !$request->all && count( $request->suscripciones ) == 0 ) || ( $request->all ) && count( $request->suscripciones) > 0 ){
            $error = [
                'error' => true,
                'message' => 'No está permitida la combinación'
            ];
            
            return response()->json($error,422);
        }
        return $this->service->calcularFacturas($request);
    }

    public function detalleSuscripcionParaFacturar(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'suscripcion' => 'required'
            ],
            [
                'required' => 'las sucripciones son requeridad'
            ]
        );

        if($validator->fails()){
            $error = [
                'error' => true,
                'message' => $validator->getMessageBag()->all()
            ];
            return response()->json($error,422);
        }

        return $this->service->detalleSuscripcionParaFacturar($request->suscripcion);
    }
}