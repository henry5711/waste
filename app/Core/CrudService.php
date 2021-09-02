<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 23/07/18
 * Time: 04:06 PM
 */

namespace App\Core;


use Carbon\Carbon;
use DomainException;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/** @property CrudRepository $repository */
class CrudService
{

    protected $model;
    protected $object;
    protected $name = "item";
    protected $namePlural = "items";
    protected $paginate = false;
    protected $limit = null;
    protected $data = [];
    protected $request;
    protected $dato;
    protected $repository;

    public function __construct(CrudRepository $repository)
    {
        $this->repository = $repository;
    }


    public function _index(Request $request){
        try{
            $query = $this->repository->_index($request);
            //Log::info("{$query}");
            if(!$query)
            {
                return response()->json([]);
            }

            return ["list"=>$query,"total"=>count($query)];

        }catch (\Exception $e){
            return $this->errorException($e);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * consultar un registro por medio de un id
     */
    public function _show($id, $request = null)
    {
        try{

            $this->data = $this->repository->_show($id);

            if(!$this->data)
            {
                return response()->json([
                    "status" => 404,
                    'message'=>$this->name. ' no existe'
                ], 404);
            }


            Log::info('Encontrado');

            return response()->json([
                $this->name => $this->data,
            ], 200);

        }catch (\Exception $e){
            return $this->errorException($e);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function _store(Request $request)
    {
        try{
            DB::beginTransaction();
            $this->object = $this->repository->_store($request);
            DB::commit();
            if($this->object){
                Log::info('Guardado');
                return response()->json([
                    "status" => 201,
                    $this->name => $this->object],
                    201);
            }
        }catch (\Exception $e){
            DB::rollBack();
            return $this->errorException($e);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * acualizar registro
     */
    public function _update($id, Request $request)
    {
        try {

            $this->object = $this->repository->_show($id);

            if (!$this->object) {
                return response()->json(['status' => 404,
                    'message' => $this->name . ' no existe'
                ], 404);
            }

            if (!$this->repository->_update($id, $request->all())){
                return response()->json([
                    'message'=>'No se pudo Modificar',
                    $this->name => $this->object
                ], 200);
            }

            return response()->json([
                'status' => 200,
                'message'=>$this->name. ' Modificado',
                $this->name=> $request->all()
            ], 200)->setStatusCode(200, "Registro Actualizado");

        }catch(\Exception $e){
            return $this->errorException($e);
        }
    }

    /**
     * @param $id
     * @param $name_pk
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * metodo para eliminar un registro
     */
    public function _destroy($id,Request $request,$name_pk = 'deleted')
    {
        try {

            $this->object = $this->repository->find($id);

            if (!$this->object) {
                return response()->json([
                    'status' => 404,
                    'message' => $this->name . ' no existe'
                ], 404);
            }
            $this->object->$name_pk = true;
            $this->object->update();
            return response()->json([
                'status' => 206,
                'message' => $this->name . ' Eliminado'
            ], 206);

        }catch (\Exception $e){
            return $this->errorException($e);
        }
    }

    public function _delete($id)
    {  
        return $this->repository->_delete($id);
    }



    public function decode(Request $request)
    {
        $token = ($request->header()['authorization'])[0];

        $tks = explode('.', $token);
        list($headb64, $bodyb64, $cryptob64) = $tks;
        $user = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64));

        if($user){
            $user->account = $request->account;
            return $user;
        }

        return null;
    }

    /**
     * @param null $item
     * @return \Illuminate\Http\JsonResponse
     * respuesta de no encontrado en formato json
     * puede recibir un string para escificar que cosa no se encontro
     */
    public function notFound($item = null)
    {
        return response()->json([
            'message'=> $item.' No Encontrado'
        ],404);
    }


    public function errorException(\Exception $e)
    {
        Log::critical("Error, archivo del peo: {$e->getFile()}, linea del peo: {$e->getLine()}, el peo: {$e->getMessage()}");
        if ($e instanceof ModelNotFoundException OR $e instanceof DomainException){
            throw $e;
        }
        return response()->json([
            "error"=>true,
            "message" => $e->getMessage()
        ], 500);
    }

    public function getNow()
    {
        return Carbon::now()->format('Y-m-d H:i:s');
    }


    /**
     * @return mixed
     */
    public function getRepository()
    {
        return $this->repository;
    }

}