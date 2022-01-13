<?php

namespace App\Http\Controllers\prodetalle;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\prodetalle;
use App\Models\suscripciones;
use App\Services\prodetalle\prodetalleService;
/** @property prodetalleService $service */
class prodetalleController extends CrudController
{
    public function __construct(prodetalleService $service)
    {
        parent::__construct($service);
    }

    public function detallesus(Request $request)
    {
        $sus=$request->suid;
        $bd=prodetalle::where('id_su',$sus)->get();
        return ["list"=>$bd,"total"=>count($bd)];
    }

    public function _store($request)
    {
        return $this->service->_store($request);
    }

    public function verificarExistencia($request){
        
        $productos = suscripciones::find($request->id);
        $productos = $productos->Productos;
        
        foreach ($productos as $viejo) {
            $band = false;
            foreach($request->productos as $nuevo){
                if($viejo->id_pro == $nuevo['id_pro']){
                    $band = true;
                }
            }

            if(!$band){
                $this->service->_delete($viejo['id']);
            }
        }
        $todos = [];
        
        foreach ($request->productos as $prod) {
            $nuevos = [];
            $bool = $productos->where('id_pro',$prod['id_pro'])->first();
            
            if(!$bool){
                $nuevos['productos'][] = $prod;
                $nuevos['id'] = $request->id;
                $todos[] = $this->service->_store(new Request($nuevos));
            }else{
                $todos[] = $this->service->_update($bool->id, new Request($prod));
            }
        }
        
        return $todos;
    }
}