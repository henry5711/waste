<?php

namespace App\Http\Controllers\suscripciones;

use App\Events\CheckHistorialBillingMasive;
use App\Http\Controllers\Controller;
use App\Models\HistorialBillingMasive;
use App\Models\suscripciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistorialBillingMasiveController extends Controller
{
    //

    public function __invoke(Request $request)
    {
        $historial = HistorialBillingMasive::addSelect([
            'suscripcion_name' => suscripciones::select('titulo')->whereColumn('suscripcion_id','suscripciones.id')
        ])->get();
        return $historial;
    }
}
