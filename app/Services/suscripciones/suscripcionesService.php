<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\suscripciones;


use App\Core\CrudService;
use App\Core\ImageService;
use App\Http\Controllers\Clientes\ClientesController;
use App\Http\Controllers\prodetalle\prodetalleController;
use App\Http\Mesh\BillingService;
use App\Models\Clientes;
use App\Repositories\prodetalle\prodetalleRepository;
use App\Services\prodetalle\prodetalleService;
use App\Models\prodetalle;
use App\Repositories\suscripciones\suscripcionesRepository;
use Illuminate\Http\Request;
use App\Models\suscripciones;
use App\Repositories\Clientes\ClientesRepository;
use App\Services\Clientes\ClientesService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

/** @property suscripcionesRepository $repository */
class suscripcionesService extends CrudService
{

    protected $name = "suscripciones";
    protected $namePlural = "suscripciones";
    protected $clientes;
    protected $productos;

    public function __construct(suscripcionesRepository $repository)
    {
        parent::__construct($repository);
        $this->clientes = new ClientesController(new ClientesService(new ClientesRepository(new Clientes)));
        $this->productos = new prodetalleController(new prodetalleService(new prodetalleRepository(new prodetalle())));
    }

    public function _store(Request $request)
    {
        DB::beginTransaction();
        try{
            
            $request['prox_cob']=$request->fec_ini;
            $request['sta']="Por Confirmar";
            
            if($request->ico != null || $request->ico != ''){
                $request['ico'] = (new ImageService)->image($request->ico);
            }

            $suscripcion = $this->repository->_store($request);
            $request['id'] = $suscripcion->id;

            $productos = $this->productos->_store($request);
            // return $productos;
            $clientes = $this->clientes->_store($request);
            // return $clientes;
                DB::commit();
            return response()->json([
                "status" => 201,
                'suscripcion' => $request->all()],
                201);
            
        }catch(Exception $e){
            DB::rollback();

            return $e;
        }
    }

    public function verDetalle($id_suscripcion){
        return $this->repository->verDetalle($id_suscripcion);
    }

    public function _update($id, Request $request)
    {
        DB::beginTransaction();
        $sus = suscripciones::find($id);
        try{
            
            $suscripcion = new Request($request->except(['productos','clientes']));

            if($request->ico != null && $request->ico != ''){
                if( ( env('APP_URL') . $sus->ico ) != $request->ico ){
                    // unlink( ltrim( $sus->ico,'\/' ) );
                    $request['ico'] = $suscripcion['ico'] = (new ImageService)->image($request->ico);
                }else{
                    unset($suscripcion['ico']);
                }
            }
            
            $this->repository->_update($id,$suscripcion);

            $clientes = new Request($request->only('clientes'));
            $clientes['id'] = $id;
            $actualizar_clientes = $this->clientes->verificarExistencia($clientes);
            // return $actualizar_clientes;

            $productos = new Request($request->only('productos'));
            $actualizar_productos = $this->productos->verificarExistencia($productos->productos,$sus);
            // return $actualizar_productos;

            DB::commit();

            return response()->json([
                'status' => 200,
                'message'=>$this->name. ' Modificado',
                $this->name=> $request->all()
            ], 200)->setStatusCode(200, "Registro Actualizado");

        }catch(Exception $e){
            DB::rollback();

            return $e->getMessage();
        }
    }
    
    public function buscarClienteId($id_client){
        return $this->repository->buscarClienteId($id_client);
    }

    public function buscarClienteFiltro($request){
        return $this->repository->buscarClienteFiltro($request);
    }

    public function generarNumero(){
        $bool = false;
        do {
            $num = random_int(0,9999);
            $bool = $this->repository->generarNumero($num);
        } while (!$bool);
        $digitos = strlen($num);
        if($digitos<4){
            $digitos = 4 - $digitos;

            for ($i=1; $i <= $digitos; $i++) { 
                $num = "0".$num;
            }
        }
        return "$num";
    }

    public function mostrarFacturas(){
       
        $suscripciones = $this->repository->mostrarFacturas();
        $sus = [];
        foreach ($suscripciones as $suscripcion) {
            $id = $suscripcion->suscripcion;
            $fecha = $suscripcion->fecha_facturacion;
            $facturas = new BillingService();
            $f = $facturas->consultarSuscripciones($id,$fecha);
            if(count($f) > 0 ){
                $suscripcion->facturas = $f;
            }else{
                $suscripcion->facturas = [];
            }
            
        }
        return $suscripciones;
    }

    public function estado($id,$estado){
        $suscripcion = suscripciones::find($id);

        if($suscripcion->sta == 'Por Confirmar' && $estado != 'Activa'){
            return response()->Json([
                'error' => true,
                'message' => 'Las suscripciones por confirmar solo pueden pasar a estado Activa'
            ],425);
        }
        if($estado == 'Por Confirmar' && ($suscripcion->sta == 'Activa' || $suscripcion->sta == 'Pausada' || $suscripcion->sta == 'Cancelada')){
            return response()->Json([
                'error' => true,
                'message' => 'Las suscripciones activas, pausadas o canceladas no pueden pasar a estado por confirmar'
            ],425);
        }

        
        return $this->repository->estado($id,$estado);
    }

    public function Filtro($request){
        return $this->repository->Filtro($request);
    }

    public function calcularFacturas($request){
        $ids = $request->all ?: $request->suscripciones;
        $suscripciones = $this->repository->obtenerSuscripciones($ids);

        $cantidad_facturas = $this->cantidadFacturas($suscripciones);
        // return $cantidad_facturas;
        
        $cantidad_clientes = $this->cantidadClientes($suscripciones->load('Clientes'));
        // return $cantidad_clientes;

        $cantidad_dinero = $this->cantidadDinero($suscripciones);
        // return $cantidad_dinero;

        return [
            'facturas_generadas'    => $cantidad_facturas,
            'clientes_facturados'   => $cantidad_clientes,
            'total_a_facturar'      => $cantidad_dinero
        ];
    }

    private function cantidadFacturas($suscripciones){
        
        $total = 0;

        foreach($suscripciones as $suscripcion){
            $contador = 0;
            $prox_cob  = Carbon::parse($suscripcion['prox_cob'])->startOfDay();
            $fecha_ini = Carbon::parse($suscripcion['fec_ini'])->startOfDay();
            $fecha_fin = Carbon::parse($suscripcion['fec_fin'])->startOfDay();
            $hoy = Carbon::now()->startOfDay();
    
            if($prox_cob->isBefore($fecha_ini)){
                $prox_cob = $fecha_ini;
            }
            
            if($hoy->isAfter($fecha_fin)){
                // si hoy es despues a la fecha de vencimiento, se va a contar desde el ultimo pago hasta la fecha de fin
                $hoy = $fecha_fin;
            }
            // return $hoy;
            /**
             * Verificar Ciclo de facturacion
            */
    
            if($suscripcion['periodo'] == 'Diaria'){
                $contador = $prox_cob->diffInDays($hoy);
            }
    
    
            if($suscripcion['periodo'] == 'Quincenal'){
                /**
                 * Facturas quincena
                */
                $quincena = $prox_cob;
                $band = true;
                $p = $quincena;
                while ($band) {
                    if($hoy->isBefore($p) || $hoy->equalTo($p)){
                        $band= false;
                    }else{
                        $p->addDays(15);
                        $contador += 1;
                    }
                }
            }
            if($suscripcion['periodo'] == 'Semanal'){
                $contador = $prox_cob->diffInWeeks($hoy);
            }
            if($suscripcion['periodo'] == 'Mensual'){
                $contador = $prox_cob->diffInMonths($hoy);
            }
            if($suscripcion['periodo'] == 'Anual'){
                $contador = $prox_cob->diffInYears($hoy);
            }

            $total += $contador;
        }
        
        return $total;

    }

    private function cantidadClientes($suscripciones){
        
        $clientes = collect();
        foreach($suscripciones as $suscripcion){
            $clientes_suscripcion = $suscripcion->clientes;
            $ids = $clientes_suscripcion->keyBy('id_client')->keys();
            $clientes = $clientes->concat($ids);
        }
        return $clientes->unique()->values()->count();
    }

    private function cantidadDinero($suscripciones){
        $total_dinero = 0;
        
        foreach($suscripciones as $suscripcion){
            $cantidad_facturas = $this->cantidadFacturas([$suscripcion]);
            $cantidad_clientes = count($suscripcion->clientes);
            $total_dinero += $cantidad_clientes * $cantidad_facturas * $suscripcion->total;
        }

        return number_format($total_dinero,3,',','.');
    }
}