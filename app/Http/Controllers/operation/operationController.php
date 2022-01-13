<?php

namespace App\Http\Controllers\operation;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\operation\operationService;
/** @property operationService $service */
class operationController extends CrudController
{
    public function __construct(operationService $service)
    {
        parent::__construct($service);
    }
}