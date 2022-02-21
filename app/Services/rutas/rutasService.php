<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\rutas;


use App\Core\CrudService;
use App\Models\operation;
use App\Models\rutas;
use App\Repositories\rutas\rutasRepository;
use Faker\Factory;
use Illuminate\Http\Request;

/** @property rutasRepository $repository */
class rutasService extends CrudService
{

    protected $name = "rutas";
    protected $namePlural = "rutas";

    public function __construct(rutasRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
       
        
        foreach ($request->operaciones as $key) {
            $ope=operation::find($key);
            $ope->status='En ruta';
            $ope->save();
        }

        $confi=false;
        do {

            $faker = Factory::create();
            $codigo = $faker->regexify('[A-Z]{5}[0-9]{3}');
    
            $bus=rutas::where('cod_rut',$codigo)->first();

            if ($bus==[] or count($bus)==0 or $bus==null) 
            {
                $request['cod_rut']=$codigo;
                $confi=true;
            }
          
          
        } while ($confi==false);
       
        $request['status']='CREADA';

        $rutu=$this->repository->_store($request);
        //return $rutu->id;
        $rutu=rutas::find($rutu->id);
        
        $rutu->Operaciones()->attach($request->operaciones);
        return response()->json([
            "status" => 201,
            $rutu],
            201);
    }

    public function _update($id, Request $request)
    {
        if($request->operaciones)
        {
            $rutu=rutas::find($id);
            $rutu->Operaciones()->sync($request->operaciones);
            return parent::_update($id,$request);
        }

        else
        {
            return parent::_update($id,$request);
        }
       
    }
}