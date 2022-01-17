<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\rutas;

use App\Core\CrudRepository;
use App\Models\rutas;

/** @property rutas $model */
class rutasRepository extends CrudRepository
{

    public function __construct(rutas $model)
    {
        parent::__construct($model);
    }

}