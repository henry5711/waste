<?php

namespace App\Http\Controllers\suscripciones;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\suscripciones;
use App\Services\suscripciones\suscripcionesService;
/** @property suscripcionesService $service */
class suscripcionesController extends CrudController
{
    public function __construct(suscripcionesService $service)
    {
        parent::__construct($service);
    }

    public function facturar()
    {
        $cobrar=suscripciones::where('sta','Activa')->get();
        dd($cobrar);
    }
}