<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\prodetalle;


use App\Core\CrudService;
use App\Repositories\prodetalle\prodetalleRepository;
use Illuminate\Http\Request;
/** @property prodetalleRepository $repository */
class prodetalleService extends CrudService
{

    protected $name = "prodetalle";
    protected $namePlural = "prodetalles";

    public function __construct(prodetalleRepository $repository)
    {
        parent::__construct($repository);
    }

    
    public function _store($request)
    {
        $bool = [];
        foreach ($request['productos'] as $producto) {
            $producto['id_susp'] = $request->id;
            $to=$producto['precio']*$producto['cantidad'];
            $producto['sub_total']=$to;
            $bool[] = $this->repository->_store($producto);
        }
        return $bool;
    }
}