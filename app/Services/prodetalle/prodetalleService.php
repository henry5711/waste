<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\prodetalle;


use App\Core\CrudService;
use App\Repositories\prodetalle\prodetalleRepository;
use Illuminate\Http\Request;
/** @property prodetalleRepository $repository */
class prodetalleService extends CrudService
{

    protected $name = "prodetalle";
    protected $namePlural = "prodetalles";

    public function __construct(prodetalleRepository $repository)
    {
        parent::__construct($repository);
    }

    
    public function _store(Request $request)
    {
        $to=$request->precio*$request->cantidad;
        $request['sub_total']=$to;
        return parent::_store($request);
    }
}