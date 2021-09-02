<?php

namespace App\Http\Controllers\suscripciones;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\suscripciones\suscripcionesService;
/** @property suscripcionesService $service */
class suscripcionesController extends CrudController
{
    public function __construct(suscripcionesService $service)
    {
        parent::__construct($service);
    }
}