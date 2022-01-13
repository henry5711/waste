<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CaseSensitive implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    protected $model;

    public function __construct($model)
    {
        $this->model = DB::table("$model")->select('*');
    }
    public function passes($attribute, $value)
    {
        $grupos = $this->model
                        ->whereRaw("lower($attribute) ilike lower('$value')")->get();

        if(count($grupos)>0){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El :attribute debe ser único.';
    }
}