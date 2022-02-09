<?php

namespace App\Http\Controllers\rutas;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\operation;
use App\Models\rutas;
use App\Services\rutas\rutasService;
/** @property rutasService $service */
class rutasController extends CrudController
{
    public function __construct(rutasService $service)
    {
        parent::__construct($service);
    }

    public function showrut($id)
    {
      
        $ruta=rutas::with('Operaciones')->find($id);
        $ruta->peso_total=$ruta->operaciones->sum('peso');
        $estado=$ruta->operaciones->where('status','En ruta');
        
        if(count($estado)==0)
        {
            $r=rutas::find($id);
            $r->status='TERMINADA';
            $r->save();
        }
        

        
        return $ruta;
    }
}