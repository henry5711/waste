<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Clientes;

use App\Core\CrudRepository;
use App\Models\Clientes;
use Illuminate\Http\Request;

/** @property Clientes $model */
class ClientesRepository extends CrudRepository
{

    public function __construct(Clientes $model)
    {
        parent::__construct($model);
    }

    

    public function _store($data)
    {
        return Clientes::create($data);
    }

}