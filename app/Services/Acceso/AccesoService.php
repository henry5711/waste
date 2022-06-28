<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Acceso;


use App\Core\CrudService;
use App\Repositories\Acceso\AccesoRepository;

/** @property AccesoRepository $repository */
class AccesoService extends CrudService
{

    protected $name = "acceso";
    protected $namePlural = "accesos";

    public function __construct(AccesoRepository $repository)
    {
        parent::__construct($repository);
    }

}