<?php

namespace App\Http\Controllers\operation;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\operation;
use App\Services\operation\operationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/** @property operationService $service */
class operationController extends CrudController
{
    public function __construct(operationService $service)
    {
        parent::__construct($service);
    }

    public function icreadas()
    {
        $ope=operation::where('status','Creada')->get();
        return  ["list"=>$ope,"total"=>count($ope)];
    }

    public function filtro(Request $request)
    {
        $op=operation::when($request->date, function($query, $interval){
            $date = explode('_', $interval);
            $date[0] = Carbon::parse($date[0])->format('Y-m-d');
            $date[1] = Carbon::parse($date[1])->format('Y-m-d');
            return $query->whereBetween(
                DB::raw("TO_CHAR(fecha,'YYYY-MM-DD')"),[$date[0],$date[1]]);
            })
        ->when($request->name,function($query,$name){
            //buscar sucursal o usuario
            return $query->where('name_sucursal','ILIKE',"%$name%");
        })->get();

        return ["list"=>$op,"total"=>count($op)];
    }
}