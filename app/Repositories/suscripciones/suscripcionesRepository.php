<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\suscripciones;

use App\Core\CrudRepository;
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
        return suscripciones::Filtro($request)->get();
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

    public function buscarCliente($id_client){
        $this->id_cli = $id_client;
        $suscripciones = suscripciones::whereHas('Clientes', function($query){
            return $query->where('id_client','=',$this->id_cli);
        })->get();

        return $suscripciones;
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
}