<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\operation;


use App\Core\CrudService;
use App\Models\operation;
use App\Repositories\operation\operationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

/** @property operationRepository $repository */
class operationService extends CrudService
{

    protected $name = "operation";
    protected $namePlural = "operations";

    public function __construct(operationRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        $request['fecha_ope']=Carbon::now();
        $request['status']='Creada';
        return parent::_store($request);
    }


    
    
}