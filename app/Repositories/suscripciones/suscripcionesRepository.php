<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\suscripciones;

use App\Core\CrudRepository;
use App\Models\Clientes;
use App\Models\suscripciones;
use Illuminate\Support\Facades\DB;
/** @property suscripciones $model */
class suscripcionesRepository extends CrudRepository
{
    protected $id_cli;
    public function __construct(suscripciones $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        return suscripciones::Filtro($request)->with(['Clientes','Productos'])->get();
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
        $suscripcion = suscripciones::with(['Clientes','Productos'])->where('id',$id)->get();

        return $suscripcion;
    }

    /**
     * Busca todas las suscripciones de un cliente
     */
    public function buscarClienteId($id_client){
        $this->id_cli = $id_client;
        $suscripciones = suscripciones::whereHas('Clientes', function($query){
            return $query->where('id_client','=',$this->id_cli);
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
}