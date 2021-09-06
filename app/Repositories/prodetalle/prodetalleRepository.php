<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\prodetalle;

use App\Core\CrudRepository;
use App\Models\prodetalle;

/** @property prodetalle $model */
class prodetalleRepository extends CrudRepository
{

    public function __construct(prodetalle $model)
    {
        parent::__construct($model);
    }

}