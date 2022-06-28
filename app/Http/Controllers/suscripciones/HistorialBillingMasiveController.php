<?php

namespace App\Http\Controllers\suscripciones;

use App\Events\CheckHistorialBillingMasive;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistorialBillingMasiveController extends Controller
{
    //

    public function __invoke(Request $request)
    {
        event(new CheckHistorialBillingMasive);
        return 'escuchando';
    }
}
