<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\config;

use App\Core\CrudRepository;
use App\Models\config;

/** @property config $model */
class configRepository extends CrudRepository
{

    public function __construct(config $model)
    {
        parent::__construct($model);
    }

}