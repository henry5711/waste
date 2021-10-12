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

        if(count($pl)>0)
        {
            foreach ($pl as $key ) {
                $f=json_decode($key->condi);
                $key->condi=$f;
              }
    
              return $pl[0];
        }

        else
        {
            return parent::_show($id);
        }
       
    }
}