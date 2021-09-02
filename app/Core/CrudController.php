<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 23/07/18
 * Time: 04:05 PM
 */

namespace App\Core;

use App\Traits\ApiResponse;
use App\Traits\ManageRoles;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/** @property CrudService $service */

class CrudController extends BaseController
{
    use ApiResponse, ManageRoles;

    public $service;
    protected $validateStore = [];
    protected $validateUpdate = [];
    protected $validateDefault = [];
    protected $policies = [];
    protected $messages = [];

    public function __construct(CrudService $service){
        $this->service = $service;
    }


    public function _index(Request $request)
    {
        return $this->service->_index($request);
    }

    public function _show($id)
    {
        return $this->service->_show($id);
    }

    public function _store(Request $request)
    {
        $validator = Validator::make($request->all(), array_merge($this->validateStore, $this->validateDefault),$this->messages);
        if ($validator->fails()) {
            return response()->json(["error"=>true,"message"=>$this->parseMessageBag($validator->getMessageBag())],422);
        }
        return $this->service->_store($request);
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function _update($id, Request $request)
    {

        $validator = Validator::make($request->all(), array_merge($this->validateUpdate, $this->validateDefault),$this->messages);
        if ($validator->fails()) {
            return response()->json(["error"=>true,"message"=>$this->parseMessageBag($validator->getMessageBag())],422);
        }
        return $this->service->_update($id, $request);
    }

    public function _destroy($id, Request $request)
    {
        return $this->service->_destroy($id, $request);
    }

    public function _delete($id)
    {
        return $this->service->_delete($id);
    }
    
    public function parseMessageBag($messageBag){
        return array_merge(array_values($messageBag->getMessages()));
    }

    public function isAdmin($request){
        if (!$this->haveRoles($request,'admin','sysadmin','superadmin')){
            throw new UnauthorizedException('unauthorized');
        }
    }

}