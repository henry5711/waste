<?php

namespace App\Http\Controllers\config;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\config\configService;
/** @property configService $service */
class configController extends CrudController
{
    public function __construct(configService $service)
    {
        parent::__construct($service);
    }
}