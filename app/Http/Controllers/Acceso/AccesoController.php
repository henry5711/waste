<?php

namespace App\Http\Controllers\Acceso;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Http\Requests\AccesoRequest;
use App\Models\Acceso;
use App\Services\Acceso\AccesoService;
use Facade\FlareClient\Http\Exceptions\BadResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/** @property AccesoService $service */
class AccesoController extends CrudController
{


    public function __construct(AccesoService $service)
    {
        parent::__construct($service);
    }

    public function _store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|string|unique:accesos',
                'descripcion' => 'required|string'
            ],
            [
                'required' => 'El campo :attribute  es requerido',
                'unique' => 'El campo :attribute debe ser unico'
            ]
        );

        if ($validator->fails()) {
            $error = [
                'error' => true,
                'message' => $validator->getMessageBag()->first()
            ];
            return response()->json($error, 422);
        }

        return $this->service->_store($request);
    }

    public function _update($id, Request $request)
    {
        $acceso = Acceso::find($id);

        $validator = Validator::make(
            $request->all(),
            [
                'nombre' => ['required', Rule::unique('accesos', 'nombre')->ignore($acceso->id)],
                'descripcion' => 'required|string'
            ],
            [
                'required' => 'El campo :attribute  es requerido',
                'unique' => 'El campo :attribute debe ser unico'
            ]
        );

        if ($validator->fails()) {
            $error = [
                'error' => true,
                'message' => $validator->getMessageBag()->first()
            ];
            return response()->json($error, 422);
        }

        return $this->service->_update($id, $request);
    }
}