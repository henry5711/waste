<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\rutas;


use App\Core\CrudService;
use App\Models\rutas;
use App\Repositories\rutas\rutasRepository;
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
}