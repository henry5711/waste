<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\rutas;

use App\Core\CrudRepository;
use App\Models\operation;
use App\Models\rutas;

/** @property rutas $model */
class rutasRepository extends CrudRepository
{

    public function __construct(rutas $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
       $ruta=rutas::with('Operaciones')->get();
       foreach ($ruta as $key) 
       {
        $key->peso_total=$key->operaciones->sum('peso');
        $estado=$key->operaciones->where('status','En ruta');
        
        if(count($estado)==0)
        {
            $r=rutas::find($key->id);
            $r->status='TERMINADA';
            $r->save();
        }
       }
        return $ruta;
    }

}