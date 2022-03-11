<?php

namespace App\Http\Controllers\config;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\config;
use App\Services\config\configService;
use Illuminate\Support\Facades\Validator;

/** @property configService $service */
class configController extends CrudController
{
    public function __construct(configService $service)
    {
        parent::__construct($service);
    }

    public function _store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'automatic_operations' => 'boolean'
            ],
            [
                'boolean' => 'El campo :attribute debe ser True o False'
            ]
        );

        if( $validator->fails() )
            return $this->errors($validator->getMessageBag()->all());
        
        return parent::_store($request);
    }

    private function errors($data){
        $error = [
            'error' => true,
            'message' => $data
        ];
        
        return response()->json($error,422);
    }
}