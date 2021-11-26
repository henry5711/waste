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

    public function __construct(suscripciones $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        $suscripciones = $this->filtro($request);
        // $suscripciones = suscripciones::all();
        return $suscripciones;
    }
    public function filtro($request){
        $suscripciones = DB::table('suscripciones')->select(['*'])
                        ->when($request->id_client,function($query,$id_client){
                            return $query->where('id_client','=',$id_client);
                        })
                        ->when($request->estado,function($query,$estado){
                            return $query->where('sta','=',$estado);
                        })
                        ->when($request->nombre,function($query,$nombre){
                            return $query->where(function($query,$nombre){
                                return $query->where('nombre','ilike',"%$nombre%")
                                            ->orwhere('correo','ilike',"%$nombre%");
                            });
                        })
                        ->when($request->numero,function($query,$numero){
                            return $query->where('numero','ilike',"%$numero%");
                        })
                        // ->when($request->fecha_ini,function($query,$inicio){
                        //     $inicio = Carbon::parse($inicio);
                            
                        // });
                        ->get();

        return $suscripciones;
    }

    public function verDetalle($id_suscripcion){
        $detalle = suscripciones::find($id_suscripcion);
        $detalle = $detalle->Productos;

        return [
            "list" => $detalle,
            "count" => count($detalle)
        ];
    }
}