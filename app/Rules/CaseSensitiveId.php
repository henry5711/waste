<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CaseSensitiveId implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    protected $modelo;
    protected $tabla;
    protected $id;
    public function __construct($tabla, $id)
    {
        $this->modelo = DB::table("$tabla")->select(['*'])->where('id','=',$id)->first();
        $this->tabla = $tabla;
    }
    public function passes($attribute, $value)
    {
        if($this->modelo->$attribute !== $value ){
            $bool = DB::table($this->tabla)->select('id')
                        ->whereRaw("lower($attribute) ilike lower('$value')")
                        ->get();
            if(count($bool) > 0){
                $exist = false;
                foreach($bool as $b){
                    if($b->id != $this->modelo->id){
                        return false;
                    }
                }
            }
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