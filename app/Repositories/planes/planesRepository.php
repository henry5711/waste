<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\planes;

use App\Core\CrudRepository;
use App\Models\planes;

/** @property planes $model */
class planesRepository extends CrudRepository
{

    public function __construct(planes $model)
    {
        parent::__construct($model);
    }

}