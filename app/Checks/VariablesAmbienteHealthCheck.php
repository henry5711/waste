<?php

namespace App\Checks;

use UKFast\HealthCheck\HealthCheck;

class VariablesAmbienteHealthCheck extends HealthCheck
{
    protected $name = 'Variables_Ambiente';
    
    public function status()
    {
        $problem = false;
        $ok = false;
        if( env('BILLING_API') == null ){
            $problem[] = 'No existe la variable BILLING_API';
        }else{
            $ok[] = 'BILLING_API = ' . env('BILLING_API');
        }

        if( env('INVENTORY_API') == NULL){
            $problem[] = 'No existe la variable INVENTORY_API';
        }else{
            $ok[] = 'INVENTORY_API = ' . env('INVENTORY_API');
        }
        

        if ($problem) {
            return $this->problem('No existen las variables de ambiente', [
                $problem
            ]);
        }
        
        return $this->okay($ok);
    }
}
