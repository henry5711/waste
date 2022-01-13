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

}