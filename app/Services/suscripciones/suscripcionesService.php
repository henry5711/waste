<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\suscripciones;


use App\Core\CrudService;
use App\Http\Controllers\prodetalle\prodetalleController;
use App\Repositories\prodetalle\prodetalleRepository;
use App\Services\prodetalle\prodetalleService;
use App\Models\prodetalle;
use App\Repositories\suscripciones\suscripcionesRepository;
use Illuminate\Http\Request;
use App\Models\suscripciones;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

/** @property suscripcionesRepository $repository */
class suscripcionesService extends CrudService
{

    protected $name = "suscripciones";
    protected $namePlural = "suscripciones";

    public function __construct(suscripcionesRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        DB::beginTransaction();
        try{
            
            $request['prox_cob']=$request->fec_ini;
            $request['sta']="Activa";
            $suscripcion = $this->repository->_store($request);
            $request['id'] = $suscripcion->id;

            $guardar_detalle = new prodetalleController(new prodetalleService(new prodetalleRepository(new prodetalle())));
            
            $guardar_detalle->_store($request);
            
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

}