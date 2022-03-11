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

    public function verificarExistencia($productos, suscripciones $sus){
        // return $productos;
        return $this->service->verificarExistencia($productos,$sus);
        
    }
}