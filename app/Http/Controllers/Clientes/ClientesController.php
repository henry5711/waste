<?php

namespace App\Http\Controllers\Clientes;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Clientes\ClientesService;
/** @property ClientesService $service */
class ClientesController extends CrudController
{
    public function __construct(ClientesService $service)
    {
        parent::__construct($service);
    }

    public function _store(Request $request)
    {
        
        $req = new Request($request->only(['clientes','id']));
        
        return $this->service->_store($req);
    }
}