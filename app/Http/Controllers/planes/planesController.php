<?php

namespace App\Http\Controllers\planes;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Core\ImageService;
use App\Models\planes;
use App\Services\planes\planesService;
use PhpParser\Node\Stmt\Foreach_;

/** @property planesService $service */
class planesController extends CrudController
{
    public function __construct(planesService $service)
    {
        parent::__construct($service);
    }

    public function planetipo(Request $request)
    {
        if($request->tipo=="usu")
        {
          $pl=planes::where('tipo','usuario')->get();
          foreach ($pl as $key ) {
            $f=json_decode($key->condi);
            $key->condi=$f;
          }
          return ["list"=>$pl,"total"=>count($pl)];
        }
        
        elseif($request->tipo=="cli")
        {
            
            $pl=planes::where('tipo','cliente')->get();
            foreach ($pl as $key ) {
              $f=json_decode($key->condi);
              $key->condi=$f;
            }
            return ["list"=>$pl,"total"=>count($pl)];
        }

        else
        {
            return response()->json(["error"=>true,"message"=> "no se selecciono un tipo de plan"],422);
        }
    }

    public function guardar(Request $request)
    {
        $exist=planes::whereRaw('lower(plan)=?',strtolower($request->plan))->first();

        if($exist)
        {
            return response()->json(["error"=>true,"message"=> "Ya existe un plan con este nombre"],422);
        }

        else
        {

            if (isset($request->icon))
            {
                $p=ucfirst($request->plan);
                $back = null;
                if($request->icon != null && $request->icon != '') {
                    $imageService = new ImageService;
                    $back= $imageService->image($request->icon);
                }
                $sp=new planes;
                $sp->plan=$p;
                $sp->precio=$request->precio;
                $sp->Periodicidad=$request->Periodicidad;
                $sp->condi=json_encode($request->condi);
                $sp->obs=$request->obs;
                $sp->tipo=$request->tipo;
                $sp->icon=$back;
                $sp->save();
    
    
                return response()->json(["status" => 201,$sp],201);
            }
            else
            {
                $p=ucfirst($request->plan);
                $sp=new planes;
                $sp->plan=$p;
                $sp->precio=$request->precio;
                $sp->Periodicidad=$request->Periodicidad;
                $sp->condi=json_encode($request->condi);
                $sp->tipo=$request->tipo;
                $sp->icon=null;
                $sp->save();
    
    
                return response()->json(["status" => 201,$sp],201);
    
            }
           

           
              
        }
    }

}