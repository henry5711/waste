<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\operation;

use App\Core\CrudRepository;
use App\Models\operation;

/** @property operation $model */
class operationRepository extends CrudRepository
{

    public function __construct(operation $model)
    {
        parent::__construct($model);
    }

}