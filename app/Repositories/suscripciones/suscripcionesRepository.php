<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\suscripciones;

use App\Core\CrudRepository;
use App\Http\Mesh\ClientService;
use App\Models\Branches;
use App\Models\Clientes;
use App\Models\suscripciones;
use Illuminate\Support\Facades\DB;
/** @property suscripciones $model */
class suscripcionesRepository extends CrudRepository
{
    
    public function __construct(suscripciones $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        $suscripciones = suscripciones::Filtro($request)->with(['Clientes','Productos']);

        $suscripciones = $request->has('paginate') ?
            $suscripciones->paginate($request->paginate) :
            $suscripciones->get();

        return  $suscripciones;
    }

    public function verDetalle($id_suscripcion){
        $detalle = suscripciones::find($id_suscripcion);
        $detalle = $detalle->Productos;

        return [
            "list" => $detalle,
            "count" => count($detalle)
        ];
    }

    public function verClientes($id_suscripcion){
        $clientes = suscripciones::find($id_suscripcion);
        $clientes = $clientes->Clientes;

        return [
            'list' => $clientes,
            'count' => count($clientes)
        ];
    }

    public function _show($id)
    {
        $suscripcion = suscripciones::with(['Productos','Clientes'])->find($id);
        
        $suscripcion['ico'] = $suscripcion->ico ? env('APP_URL').$suscripcion->ico : null;
        return $suscripcion;
    }

    /**
     * Busca todas las suscripciones de un cliente
     */
    public function buscarClienteId($id_client){
        
        $suscripciones = suscripciones::whereHas('Clientes', function($query) use ($id_client){
            return $query->where('id_client','=',$id_client);
        })->get();

        return $suscripciones;
    }

    public function buscarClienteFiltro($request){
        return Clientes::Filtro($request)->get();
    }

    public function generarNumero($numero){
        $bool = suscripciones::where('numero',$numero)->first();

        if($bool){
            return false;
        }
        if(!$bool){
            return true;
        }
    }

    public function mostrarFacturas(){
        $suscripciones = DB::table('facturas_generadas')
        ->select([
            'suscripcion',
            'fecha_facturacion'
        ])
        ->orderBy('fecha_facturacion','desc')
        ->get();

        return $suscripciones;
    }

    public function estado($id,$estado){
        $suscripcion = suscripciones::find($id);
        $suscripcion->sta = $estado;
        $suscripcion->save();
        return response()->json([
            'status' => 202,
            'message' => 'la suscripción está ahora en estado: ' .$estado
        ],200);
    }

    public function _delete($id)
    {
        $suscripcion = suscripciones::find($id);
        $suscripcion->Productos()->delete();
        $suscripcion->Clientes()->delete();
        return parent::_delete($id);
    }

    public function Filtro($request){
        return null;
    }

    public function obtenerSuscripciones($ids){
        $suscripciones = ( $ids === true ) ? suscripciones::facturar()->get() : suscripciones::facturar()->find($ids);
        return $suscripciones;
    }

    public function obtenerSuscripcionesOperaciones($ids){
        $suscripciones = ( $ids === true ) ? suscripciones::operations()->get() : suscripciones::operations()->find($ids);
        return $suscripciones;
    }
}