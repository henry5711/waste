<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\planes;


use App\Core\CrudService;
use App\Core\ImageService;
use App\Models\planes;
use App\Models\suscrip;
use App\Models\suscripciones;
use App\Repositories\planes\planesRepository;
use Illuminate\Http\Request;


/** @property planesRepository $repository */
class planesService extends CrudService
{

    protected $name = "planes";
    protected $namePlural = "planes";

    public function __construct(planesRepository $repository)
    {
        parent::__construct($repository);
    }

/*
    public function _store(Request $request)
    {
        $exist=planes::whereRaw('lower(plan)=?',strtolower($request->plane))->first();
     
        if($exist)
        {
            return response()->json(["error"=>true,"message"=> "Ya existe un plan con este nombre"],422);
        }

        else
        {
        
           // $p=ucfirst($request->plane);
            //$request['plan']=$p;
          //  $imageService = new ImageService;
           // $back= $imageService->image($request->input('ico'));
           // $request['icon']=$back;

            return parent::_store($request);
              
        }
    }
    */

    public function _update($id, Request $request)
    {
       
            $exist=planes::whereRaw('lower(plan)=?',strtolower($request->plan))->first();

            if($exist and $exist->id != $id){
                return response()->json(["error"=>true,"message"=> "Ya existe un plan con este nombre"],422);
            }

            else
            {
                if($request->input('icon'))
                {
                    $p=ucfirst($request->plan);
                    $request['plan']=$p;
                    $imageService = new ImageService;
                    $back= $imageService->image($request->input('icon'));
                    $request['icon']=$back;
                    return parent::_update($id,$request);
                }
                else
                {
                    return parent::_update($id,$request);
                }
           
              
            }
        
    }

    public function _delete($id)
    {
        $pla=suscripciones::where('id_sus',$id)->count();

        if($pla>0)
        {
            return response()->json(["error"=>true,"message"=> "Este plan tiene suscripciones no se puede eliminar "],422);
        }
        else
        {
            return parent::_delete($id);
        }
    }

}