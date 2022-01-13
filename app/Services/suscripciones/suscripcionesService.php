<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\suscripciones;


use App\Core\CrudService;
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
            $suscripcion = $this->repository->_store($request);
            $request['id'] = $suscripcion->id;
            
            $this->productos->_store($request);
            $this->clientes->_store($request);

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

        try{
            $suscripcion = new Request($request->except(['productos','clientes']));
            $this->repository->_update($id,$suscripcion);

            $clientes = new Request($request->only('clientes'));
            $clientes['id'] = $id;
            $actualizar_clientes = $this->clientes->verificarExistencia($clientes);
            // return $actualizar_clientes;

            $productos = new Request($request->only('productos'));
            $productos['id'] = $id;
            $actualizar_productos = $this->productos->verificarExistencia($productos);
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

}