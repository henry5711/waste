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
        $pl=planes::all();
        foreach ($pl as $key ) {
            $f=json_decode($key->condi);
            $key->condi=$f;
          }
          return $pl;
    }

    public function _show($id)
    {
        
        $pl=planes::where('id',$id)->get();
        if(!$pl)
            {
                return response()->json([
                    "status" => 404,
                    'message'=>'Plan no existe'
                ], 404);
            }
        else
        {
        foreach ($pl as $key ) {
            $f=json_decode($key->condi);
            $key->condi=$f;
          }

          return $pl;
        }
    }
}