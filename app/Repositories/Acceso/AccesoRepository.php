<?php

/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Acceso;

use App\Core\CrudRepository;
use App\Models\Acceso;

/** @property Acceso $model */
class AccesoRepository extends CrudRepository
{

    public function __construct(Acceso $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        $accesos = Acceso::filtro($request)->get();

        return $accesos;
    }
}