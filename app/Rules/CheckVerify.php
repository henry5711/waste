<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CheckVerify implements Rule
{
    /**
     * Verifica si el rango de fechas corresponde al ciclo de facturación.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    protected $start;
    protected $end;
    protected $cicle = [
        'Diaria',
        'Semanal',
        'Quincenal',
        'Mensual',
        'Anual'
    ];

    /**
     * Constructor
     *
     * @param date $start
     * @param date $end
     */
    public function __construct($start,$end)
    {
        $this->start    = $start;
        $this->end      = $end;
    }

    public function passes($attribute, $value)
    {
        return $this->checkDates($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El rango de fechas no concuerda con el ciclo de facturación.';
    }

    /**
     * Checando rango de fechas
     *
     * @param string $value
     * @return bool
     */
    private function checkDates($value){
        
        $bool = false;
        
        $fecha_ini = Carbon::parse($this->start)->startOfDay();
        $fecha_fin = Carbon::parse($this->end)->endOfDay();
        
        /**
         * Verificar Ciclo de facturacion
        */
        
        if($value == 'Diaria'){
            $bool = ( $fecha_ini->diffInDays($fecha_fin) > 0 ) ? : false ;
        }


        if($value == 'Quincenal'){
            /**
             * Facturas quincena
            */
            $band = true;
            $p = $fecha_ini;
            
            while ($band) {
                $p->addDays(15);
                if($p->isBefore($fecha_fin) || $p->equalTo($fecha_fin)){
                    $bool = true;
                }else{
                    $band= false;
                }
            }
            
        }
        if($value == 'Semanal'){
            $bool = ( $fecha_ini->diffInWeeks($fecha_fin) > 0 ) ? : false;
        }
        if($value == 'Mensual'){
            
            $bool = ( $fecha_ini->diffInMonths($fecha_fin) > 0 ) ? : false;
        }
        if($value == 'Anual'){
            $bool = ( $fecha_ini->diffInYears($fecha_fin) > 0 ) ? : false;
        }
        
        // dd($bool);
        return $bool;

    }
}