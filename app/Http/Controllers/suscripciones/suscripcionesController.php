<?php

namespace App\Http\Controllers\suscripciones;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Events\CheckHistorialBillingMasive;
use App\Http\Mesh\BillingService;
use App\Jobs\SendBillingMasive;
use App\Models\HistorialBillingMasive;
use App\Models\suscripciones;
use App\Rules\CaseSensitive;
use App\Rules\CaseSensitiveId;
use App\Rules\CheckVerify;
use App\Services\suscripciones\suscripcionesService;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

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
                'estado' => Rule::in(['Activa','Pausada','Cancelada','Facturar','Operaciones'])
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
                'numero'    =>  [ new CaseSensitive('suscripciones') ],
                'clientes'  =>  [ 'required' ],
                'titulo'    =>  [ new CaseSensitive('suscripciones') ],
                'periodo'   =>  [ 
                                    new CheckVerify($request->fec_ini,$request->fec_fin), 
                                    Rule::in(['Diaria','Semanal','Quincenal','Mensual','Anual', 'Por recogida'])
                                ],

            ],
            [
                'required'  => 'El campo :attribute es requerido',
                'in'        => 'El campo :attribute debe ser: :values'
            ],
            [
                'numero' => 'numero de suscripcion'
            ]
            );
        if ($validator->fails()) {
            return response()->json(["error"=>true,"message"=>$this->parseMessageBag($validator->getMessageBag())[0][0]],422);
        }

        $request['prox_operation'] = Carbon::parse($request->fec_ini)->startOfDay();
        
        return $this->service->_store($request);
    }

    public function _update($id, Request $request)
    {
        $suscripcion = suscripciones::findOrFail($id);
        $validator = Validator::make(
            $request->all(),
            [
               'numero' => [new CaseSensitiveId('suscripciones',$id) ],
               'clientes' => [ 'required' ],
               'titulo' => [ new CaseSensitiveId('suscripciones',$id) ],
               'periodo'   =>  [ 
                    new CheckVerify($request->fec_ini,$request->fec_fin), 
                    Rule::in(['Diaria','Semanal','Quincenal','Mensual','Anual', 'Por recogida'])
                ],
            ],
            [
                'required'  => 'El campo :attribute es requerido',
                'in'        => 'El campo :attribute debe ser: :values'
            ],
            );
        if ($validator->fails()) {
            return response()->json(["error"=>true,"message"=>$this->parseMessageBag($validator->getMessageBag())[0][0]],422);
        }

        /* if($suscripcion->sta != 'Por Confirmar'){
            $error = [
                'error' => true,
                'message' => 'Solo se pueden editar las suscripciones en estado: Por Confirmar'
            ];
            return response()->json([$error,422]);
        } */

        $request['prox_operation'] = Carbon::parse($request->fec_ini)->startOfDay();
        
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
       
        SendBillingMasive::dispatch($request->suscripciones, $json);
        
        return response()->json('las suscripciones estan siendo procesadas');
    }


    public function editarproxifecha(Request $request)
    {

        Log::info('llego el json: ', $request->all());
        foreach($request->list as $s)
        {
            $susc = $s['id'];
            $up=suscripciones::find($susc);
            $up->prox_cob=$s['cobro'];
            $date = Carbon::parse($s['cobro'])->format('Y-m-d');
            if($up->fec_fin == $date ){
                $up->sta = 'Finalizada';
            }
            $up->save();
            
            $historial = HistorialBillingMasive::where('suscripcion_id',$s['id'])
                            ->where('status','En Proceso')
                            ->first();
                            
            $status = $s['cantidad esperada'] == $s['cantidad real'] ? 'Finalizada' : 'Error';
            $historial->expected_quantity = $s['cantidad esperada'];
            $historial->real_quantity = $s['cantidad real'];
            $historial->status = $status;
            $historial->save();
            CheckHistorialBillingMasive::dispatch($s['id'], $s['cantidad esperada'], $s['cantidad real'], $status);
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

        $bool = suscripciones::Facturar()->find($request->suscripcion); 
        if(!$bool){
            $error = [
                'error'   => true,
                'message' => 'La operación no esta activa o no está lista para emitir una operacion'
            ];
            return response()->json($error,422);
        }
        return $this->service->detalleSuscripcionParaFacturar($request->suscripcion);
    }

    public function generateOperations(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'suscripciones'    => 'required|array',
                'suscripciones.*'  => 'integer|exists:suscripciones,id'
            ],
            [
                'integer'   => 'El campo :attribute debe ser un número',
                'required'  => 'El campo :attribute es requerido',
                'exists'    => 'La id del campo :attribute no existe en la base de datos'
            ]
        );

        if($validator->fails()){
            $error = [
                'error' => true,
                'message' => $validator->getMessageBag()->all()
            ];
            
            return response()->json($error,422);
        }

        return $this->service->generateOperations($request);
    }

    public function calculateOperations(Request $request){
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
        return $this->service->calculateOperations($request);
    }

    public function detailSuscriptionForOperation(Request $request){
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
        
        $bool = suscripciones::Operations()->find($request->suscripcion); 
        if(!$bool){
            $error = [
                'error'   => true,
                'message' => 'La operación no esta activa o no está lista para emitir una operacion'
            ];
            return response()->json($error,422);
        }
        return $this->service->detailSuscriptionForOperation($request->suscripcion);

    }

    public function SuscripcionMasiveCounter($id, Request $request){
        $suscripcion = suscripciones::find($id);
        if($suscripcion != '' && $suscripcion != null){
            $historial = HistorialBillingMasive::where('suscripcion_id',$id)->first();
            $historial->real_quantity += 1;
            $historial->save();
            $historial->fresh();
    
            CheckHistorialBillingMasive::dispatch($suscripcion->id,$historial->expected_quantity, $historial->real_quantity);
            return response()->json(['ok']);
        }

        return response()->json(['error'=>true],422);
    }

}