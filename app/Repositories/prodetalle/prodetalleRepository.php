<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\prodetalle;

use App\Core\CrudRepository;
use App\Models\prodetalle;
use Illuminate\Http\Request;

/** @property prodetalle $model */
class prodetalleRepository extends CrudRepository
{

    public function __construct(prodetalle $model)
    {
        parent::__construct($model);
    }
    
    public function _store($data)
    {
        // $detalle = new prodetalle();
        // $detalle
        $detalle = prodetalle::create($data);
        return $detalle;
        // return $this->model::query()->create($this->$data);
    }

    public function guardar($data)
    {
        // $detalle = new prodetalle();
        // $detalle
        $detalle = prodetalle::create($data);
        return $detalle;
        // return $this->model::query()->create($this->$data);
    }
    
    public function actualizar($id, $data){
        $prodetalle = prodetalle::find($id);
        return $prodetalle->update($data);
    }
    public function verificarExistencia($id_productos, $viejos){
        $eliminar = $viejos->diff(prodetalle::whereIn('id',$id_productos)->get());

        return $eliminar;
    }

}