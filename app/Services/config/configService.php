<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\config;


use App\Core\CrudService;
use App\Models\config;
use App\Repositories\config\configRepository;
use Illuminate\Http\Request;

/** @property configRepository $repository */
class configService extends CrudService
{

    protected $name = "config";
    protected $namePlural = "configs";

    public function __construct(configRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        $con=config::all();
        
        if (count($con) >0) 
        {
            return response()->json(["error" => true, "message" => "No se puede crear mas de una configuracion"], 400);
        }

        else
        {
            return parent::_store($request);
        }
    }

}