<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\prodetalle;


use App\Core\CrudService;
use App\Http\Mesh\InventoryService;
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
            if(!array_key_exists('id_pro',$producto)){
               $crear_producto = new InventoryService();
               $crear_producto = $crear_producto->guardarProducto($producto);
               $producto['id_pro'] = $crear_producto['id'];
            }
            $producto['id_susp'] = $request->id;
            $to=$producto['precio']*$producto['cantidad'];
            $producto['sub_total']=$to;
            $bool[] = $this->repository->_store($producto);
        }
        return $bool;
    }
}