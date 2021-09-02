<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 23/07/18
 * Time: 04:06 PM
 */

namespace App\Core;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
/** @property CrudModel $model */
class CrudRepository
{
    protected $model;
    public $data = [];

    public function __construct($model = null)
    {
        /** @var CrudModel model */
        $this->model = $model;
    }

    public function _index($request = null, $user = null)
    {
        if (isset($request->where)){
            return $this->model->doWhere($request)->get();
        }
        return $this->model::all();
    }


    public function _show($id)
    {
        return $this->model::find($id);
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function _store(Request $data)
    {
        if (count($this->data) == 0) {
            $this->data = ( $data instanceof Request) ?  $data->all() : $data ;
        }
        return $this->model::query()->create($this->data);
    }

    public function _update($id, $data)
    {

        $object = $this->model::findOrFail($id);
        $data = ( $data instanceof Request) ?  $data->all() : $data ;
        $object->update($data);
        if($object)
            return $object;
        else
            return null;

    }

    public function _delete($id){
        try{
            $object = $this->model::findOrFail($id);
            $object->delete();
            return response()->json(['message' => 'deleted'],200);
        
        }catch(\Exception $e){
            return ['status' => 500,
                    'message' => $e->getMessage()];
        }
        
    }

    public function errorException(\Exception $e)
    {
        Log::critical("Error, archivo del peo: {$e->getFile()}, linea del peo: {$e->getLine()}, el peo: {$e->getMessage()}");
        return response()->json([
            "message" => "Error de servidor",
            "exception" => $e->getMessage(),
            "file" => $e->getFile(),
            "line" => $e->getLine(),
            "code" => $e->getCode(),
            // "error" => $this->runError()
        ], 500);
    }

    /* public function exceptionPdo(\Exception $e, $value =  null)
     {

     }*/

    public function select($select)
    {
        $query = $this->model::select($select);
        return $query->get();
    }

    public function saveModel($model, $data)
    {
        if($data instanceof Request)
            $data = $data->all();

        if($new = $model::create($data)){
            return $new;
        }
        return null;
    }

    public function getModel(){
        return $this->model;
    }

    public function find($id){
        return $this->model::find($id);
    }
    /**
     * @named getQueryString() Funcion para obtener el Sql Plano
     * @param Builder $cadena
     * @return string
     */
    public function getQueryString(Builder $cadena){
        $sql = str_replace('?', "'?'", $cadena->toSql());
        return vsprintf(str_replace('?', '%s', $sql), $cadena->getBindings());
    }
}