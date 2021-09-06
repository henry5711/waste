<?php

namespace App\Http\Controllers\prodetalle;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\prodetalle;
use App\Services\prodetalle\prodetalleService;
/** @property prodetalleService $service */
class prodetalleController extends CrudController
{
    public function __construct(prodetalleService $service)
    {
        parent::__construct($service);
    }

    public function detallesus(Request $request)
    {
        $sus=$request->suid;
        $bd=prodetalle::where('id_su',$sus)->get();
        return ["list"=>$bd,"total"=>count($bd)];
    }
}