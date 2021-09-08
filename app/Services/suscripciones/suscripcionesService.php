<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\suscripciones;


use App\Core\CrudService;
use App\Repositories\suscripciones\suscripcionesRepository;
use Illuminate\Http\Request;

/** @property suscripcionesRepository $repository */
class suscripcionesService extends CrudService
{

    protected $name = "suscripciones";
    protected $namePlural = "suscripciones";

    public function __construct(suscripcionesRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        $request['prox_cob']=$request->fec_ini;
        $request['sta']="Activa";
        return parent::_store($request);
    }

}