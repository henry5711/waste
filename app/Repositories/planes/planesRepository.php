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

    public function _index($request = null, $user = null)
    {
        $planes = planes::with('accesos')->get();

        return $planes;
    }

    public function _show($id)
    {
        $plan = planes::find($id);

        $plan['icon'] = $plan->icon != null && $plan->icon != '' ? env('APP_URL') . $plan->icon : null;
        $plan->load('accesos');
        return $plan;
    }
}