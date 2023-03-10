<?php

namespace App\Http\Controllers\Clientes;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\suscripciones;
use App\Services\Clientes\ClientesService;
/** @property ClientesService $service */
class ClientesController extends CrudController
{
    public function __construct(ClientesService $service)
    {
        parent::__construct($service);
    }

    public function _store(Request $request)
    {
        
        $req = new Request($request->only(['clientes','id']));
        
        return $this->service->_store($req);
    }

    public function verificarExistencia($request){
        $clientes_viejos = suscripciones::find($request->id);
        $clientes_viejos = $clientes_viejos->Clientes;
        $elim = $agg = 0;

        foreach($clientes_viejos as $c_v){
            $band = false;
            foreach ($request['clientes'] as $cliente) {
                if($c_v['id_sucursal'] == $cliente['id_sucursal']){
                    $band = true;
                }
            }

            if(!$band){
                $this->_delete($c_v['id']);
                $elim += 1;
            }
        }

        $nuevos = [];

        foreach ($request->clientes as $c) {
            $bool = $clientes_viejos->where('id_sucursal',$c['id_sucursal'])->first();

            if(!$bool){
                $nuevos['clientes'][] = $c;
            }
        }
        if(count($nuevos)>0){
            $agg = count($nuevos);
            $nuevos['id'] = $request->id;
            return $this->service->_store(new Request($nuevos));
        }
        return response()->json(['cliente añadidos'=> $agg,"eliminador"=>$elim]);
    }

}