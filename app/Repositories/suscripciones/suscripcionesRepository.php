<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\suscripciones;

use App\Core\CrudRepository;
use App\Models\suscripciones;

/** @property suscripciones $model */
class suscripcionesRepository extends CrudRepository
{

    public function __construct(suscripciones $model)
    {
        parent::__construct($model);
    }

}