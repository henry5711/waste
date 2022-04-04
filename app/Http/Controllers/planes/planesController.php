<?php

namespace App\Http\Controllers\planes;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Core\ImageService;
use App\Models\planes;
use App\Services\planes\planesService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpParser\Node\Stmt\Foreach_;

/** @property planesService $service */
class planesController extends CrudController
{
    public function __construct(planesService $service)
    {
        parent::__construct($service);
    }

    public function planetipo(Request $request)
    {
        if ($request->tipo == "usu") {
            $pl = planes::where('tipo', 'usuario')->get();
            foreach ($pl as $key) {
                $f = json_decode($key->condi);
                $key->condi = $f;
            }
            return ["list" => $pl, "total" => count($pl)];
        } elseif ($request->tipo == "cli") {

            $pl = planes::where('tipo', 'cliente')->get();
            foreach ($pl as $key) {
                $f = json_decode($key->condi);
                $key->condi = $f;
            }
            return ["list" => $pl, "total" => count($pl)];
        } else {
            return response()->json(["error" => true, "message" => "no se selecciono un tipo de plan"], 422);
        }
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'plan' => 'required|string|unique:planes',
                'icon' => 'nullable|string',
                'obs' => 'nullable|string',
                'precio' => 'numeric|min:0,1',
                'Periodicidad' => [Rule::in(['Semanal', 'Quincenal', 'Mensual', 'Anual'])],
                'accesos' => 'required|array',
                'accesos.*' => 'exists:accesos,id',
                'propietario' => 'required',
                'id_propietario' => 'required',

            ],
            [
                'required' => 'El campo :attribute es requerido',
                'unique' => 'El campo :attribute debe ser único',
                'numeric' => 'El campo :attribute debe ser un número',
                'min' => 'El campo :attribute debe ser superior a :value',
                'in' => 'El campo :attribute debe ser: :values',
                'exists' => 'El id del campo :attribute no existe en la base de datos'
            ]
        );

        if ($validator->fails()) {
            $error = [
                'error' => true,
                'message' => $validator->getMessageBag()->first()
            ];
            return response()->json($error, 422);
        }
        $back = null;
        try {
            //code...
            if ($request->icon != null && $request->icon != '') {
                $imageService = new ImageService;
                $back = $imageService->image($request->icon);
            }
            $request['icon'] = $back;
            $plan = planes::create($request->all());
            $plan->accesos()->attach($request->accesos);
            $request['id'] = $plan->id;
            return response()->json(["status" => 201, 'plan' => $request->all()], 201);
        } catch (\Exception $e) {
            //throw $th;
            $error = [
                'error' => true,
                'message' => $e->getMessage()
            ];
            return response()->json($error, 422);
        }
    }

    public function _update($id, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'plan' => ['required', 'string', Rule::unique('planes')->ignore($id)],
                'icon' => 'nullable|string',
                'obs' => 'nullable|string',
                'precio' => 'numeric|min:0,1',
                'Periodicidad' => [Rule::in(['Semanal', 'Quincenal', 'Mensual', 'Anual'])],
                'accesos' => 'required|array',
                'accesos.*' => 'exists:accesos,id',
                'propietario' => 'required',
                'id_propietario' => 'required',

            ],
            [
                'required' => 'El campo :attribute es requerido',
                'unique' => 'El campo :attribute debe ser único',
                'numeric' => 'El campo :attribute debe ser un número',
                'min' => 'El campo :attribute debe ser superior a :value',
                'in' => 'El campo :attribute debe ser: :values',
                'exists' => 'El id del campo :attribute no existe en la base de datos'
            ]
        );

        if ($validator->fails()) {
            $error = [
                'error' => true,
                'message' => $validator->getMessageBag()->first()
            ];
            return response()->json($error, 422);
        }
        $back = null;
        try {
            //code...
            $plan = planes::find($id);
            if ($request->icon != null && $request->icon != '') {
                if ($request->icon != env('APP_URL') . $plan->icon) {
                    $imageService = new ImageService;
                    $back = $imageService->image($request->icon);
                    $request['icon'] = $back;
                } else {
                    unset($request['icon']);
                }
            }
            $plan->update($request->all());
            $plan->accesos()->sync($request->accesos);
            return response()->json(["status" => 201, 'plan' => $request->all()], 201);
        } catch (\Exception $e) {
            //throw $th;
            $error = [
                'error' => true,
                'message' => $e->getMessage()
            ];
            return response()->json($error, 422);
        }
    }
}