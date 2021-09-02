<?php

namespace App\Http\Controllers\planes;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Core\ImageService;
use App\Models\planes;
use App\Services\planes\planesService;
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
          return ["list"=>$pl,"total"=>count($pl)];
        }
        
        elseif($request->tipo=="cli")
        {
            $pl=planes::where('tipo','cliente')->get();
            return ["list"=>$pl,"total"=>count($pl)];
        }

        else
        {
            return response()->json(["error"=>true,"message"=> "no se selecciono un tipo de plan"],422);
        }
    }

    public function guardar(Request $request)
    {
        $exist=planes::whereRaw('lower(plan)=?',strtolower($request->plane))->first();

        if($exist)
        {
            return response()->json(["error"=>true,"message"=> "Ya existe un plan con este nombre"],422);
        }

        else
        {

            if (isset($request->ico))
            {
                $p=ucfirst($request->plane);
                $imageService = new ImageService;
                $back= $imageService->image($request->ico);
    
                $sp=new planes;
                $sp->plan=$p;
                $sp->precio=$request->precio;
                $sp->Periodicidad=$request->Periodicidad;
                $sp->condi=$request->condi;
                $sp->obs=$request->obs;
                $sp->tipo=$request->tipo;
                $sp->icon=$back;
                $sp->save();
    
    
                return response()->json(["status" => 201,$sp],201);
            }
            else
            {

            }
            $p=ucfirst($request->plane);
            $sp=new planes;
            $sp->plan=$p;
            $sp->precio=$request->precio;
            $sp->Periodicidad=$request->Periodicidad;
            $sp->condi=$request->condi;
            $sp->obs=$request->obs;
            $sp->tipo=$request->tipo;
            $sp->icon=null;
            $sp->save();


            return response()->json(["status" => 201,$sp],201);


           
              
        }
    }

}