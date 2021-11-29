<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Clientes;


use App\Core\CrudService;
use App\Repositories\Clientes\ClientesRepository;
use Illuminate\Http\Request;

/** @property ClientesRepository $repository */
class ClientesService extends CrudService
{

    protected $name = "clientes";
    protected $namePlural = "clientes";

    public function __construct(ClientesRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        $cl = [];
        foreach ($request->clientes as $cliente) {
            $cliente['id_suscripcion'] = $request->id;
            $this->repository->_store($cliente);
            $cl[] = $cliente;
        }
        return $cl;
    }
}