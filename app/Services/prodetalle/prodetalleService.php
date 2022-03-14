<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\prodetalle;


use App\Core\CrudService;
use App\Http\Mesh\InventoryService;
use App\Models\suscripciones;
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
               $producto['id_pro'] = "0";
            }
            $producto['id_susp'] = $request->id;
            $to = $producto['precio'] * $producto['cantidad'];
            $producto['sub_total'] = $to;
            $bool[] = $this->repository->_store($producto);
        }
        return $bool;
    }

    public function verificarExistencia($productos, suscripciones $sus){
        
        $productos = collect($productos);
        $viejos = $sus->Productos;

        $id_productos = $productos->keyBy('id')->keys()->filter();
        $eliminar = $this->repository->verificarExistencia($id_productos,$viejos);
        $this->eliminarDetalle($eliminar);
        foreach ($productos as $item) {
            if(!array_key_exists('id',$item) || $item['id'] == 0){
                
                $item['id_susp'] = $sus->id;
                $to = $item['precio'] * $item['cantidad'];
                $item['sub_total'] = $to;
                $this->repository->guardar($item);
            }else{
                $to = $item['precio'] * $item['cantidad'];
                $item['sub_total'] = $to;
                // dd($item['id']);
                
                $this->repository->actualizar($item['id'],$item);
            }
            
        }
        return $viejos;
    }

    private function eliminarDetalle($eliminar){
        if($eliminar->count() > 0){
            foreach($eliminar as $el){
                $this->repository->_delete($el['id']);
            }
            return true;
        }        
        return false;
    }
}