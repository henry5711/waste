<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\suscripciones;


use App\Core\CrudService;
use App\Repositories\suscripciones\suscripcionesRepository;

/** @property suscripcionesRepository $repository */
class suscripcionesService extends CrudService
{

    protected $name = "suscripciones";
    protected $namePlural = "suscripciones";

    public function __construct(suscripcionesRepository $repository)
    {
        parent::__construct($repository);
    }

}