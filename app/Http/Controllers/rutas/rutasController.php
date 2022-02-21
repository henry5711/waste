<?php

namespace App\Http\Controllers\rutas;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\operation;
use App\Models\rutas;
use App\Services\rutas\rutasService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    public function filtro(Request $request)
    {
        $op=rutas::when($request->date, function($query, $interval){
            $date = explode('_', $interval);
            $date[0] = Carbon::parse($date[0])->format('Y-m-d');
            $date[1] = Carbon::parse($date[1])->format('Y-m-d');
            return $query->whereBetween(
                DB::raw("TO_CHAR(fec_ruta,'YYYY-MM-DD')"),[$date[0],$date[1]]);
            })
        ->when($request->cod,function($query,$code){
            //buscar sucursal o usuario
            return $query->where('cod_rut','ILIKE',"%$code%");
        })
        ->when($request->chofer,function($query,$chofer){
            //buscar sucursal o usuario
            return $query->where('cho_name','ILIKE',"%$chofer%");
        })
        ->when($request->sta,function($query,$sta){
            //buscar sucursal o usuario
            return $query->where('status','ILIKE',"%$sta%");
        })->get();

        return ["list"=>$op,"total"=>count($op)];
    }

    public function  repofil(Request $request)
    {
        
    }
}