<?php

namespace App\Jobs;

use App\Http\Controllers\operation\operationController;
use App\Http\Controllers\suscripciones\suscripcionesController;
use App\Models\operation;
use App\Models\suscripciones;
use App\Repositories\operation\operationRepository;
use App\Repositories\suscripciones\suscripcionesRepository;
use App\Services\operation\operationService;
use App\Services\suscripciones\suscripcionesService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class CreateSuscriptionOperations extends Job
{

    protected $operation;
    protected $suscripciones;
    protected $date;
    protected $susController;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    

    public function __construct($suscripciones = null)
    {
       $this->susController = new suscripcionesController(new suscripcionesService(new suscripcionesRepository(new suscripciones())));
        
        $this->suscripciones  = $suscripciones;
        $this->operation    = new operationController(new operationService(new operationRepository(new operation())));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nueva_fecha = null;
        if($this->suscripciones === null){
            $this->suscripciones = suscripciones::operations()->get();
            Log::info($this->suscripciones);
        }
        foreach ($this->suscripciones as $suscripcion) {

            $ciclos = $this->suscriptionCycle($suscripcion,'prox_operation');

            $prox_operation = Carbon::parse($suscripcion->prox_operation);
            for($i = 1; $i <= $ciclos; $i++){
                
                if($suscripcion['periodo'] == 'Diaria'){
                    $nueva_fecha = $prox_operation->addDay();
                }
                if($suscripcion['periodo'] == 'Quincenal'){
                    $nueva_fecha = $prox_operation->addDays(15);
                }
                if($suscripcion['periodo'] == 'Semanal'){
                    $nueva_fecha = $prox_operation->addWeek();
                }
                if($suscripcion['periodo'] == 'Mensual'){
                    $nueva_fecha = $prox_operation->addMonth();
                }
                if($suscripcion['periodo'] == 'Anual'){
                    $nueva_fecha = $prox_operation->addYear();
                }

            
                Log::info($suscripcion->Clientes->count());
                foreach($suscripcion->Clientes as $clientes){
                    Log::info($clientes);
                    
                    $store = [
                        'ids'           => $clientes['id_sucursal'],
                        'name_sucursal' => $clientes['nombre_sucursal'],
                        'coordenada'    => $clientes['coordenada_sucursal'],
                        'fecha'         => $nueva_fecha,
                        'tipo'          => 'web',
                        'usu/cli'       => 'cliente',
                        'fecha_ope'     => Carbon::now(),
                        'status'        => 'Creada'
                    ];
                    Log::info($store);
                    operation::create($store);
                    $store = null;
                }
        
                $suscripcion->prox_operation = $nueva_fecha;
                if($nueva_fecha->format('Y-m-d') == $suscripcion->fec_fin){
                    $suscripcion->sta = 'Finalizada';
                }
                $suscripcion->save();
            }
            
        }
    }

    /**
     * Retorna un entero con el respectivo conteo de días, semanas, quincenas
     * meses o años de una suscripción
     * @param App\Models\suscripciones $suscripciones
     * @return int
     */
    private function suscriptionCycle($suscripcion, $prox = 'prox_cob'){

        $contador = 0;

        $prox_cob  = Carbon::parse($suscripcion[$prox]);
        $fecha_ini = Carbon::parse($suscripcion['fec_ini'])->startOfDay();
        $fecha_fin = Carbon::parse($suscripcion['fec_fin'])->endOfDay();
        $hoy = Carbon::now()->startOfDay();

        if($prox_cob->isBefore($fecha_ini)){
            $prox_cob = $fecha_ini;
        }
        // return $prox_cob;
        
        if($hoy->isAfter($fecha_fin)){
            // si hoy es despues a la fecha de vencimiento, se va a contar desde el ultimo pago hasta la fecha de fin
            $hoy = $fecha_fin;
        }
        // return $hoy;
        /**
         * Verificar Ciclo de facturacion
        */
        
        if($suscripcion['periodo'] == 'Diaria'){
            $contador = $prox_cob->diffInDays($hoy);
        }


        if($suscripcion['periodo'] == 'Quincenal'){
            /**
             * Facturas quincena
            */
            $band = true;
            $p = $prox_cob;
            
            while ($band) {
                $p->addDays(15);
                if($p->isBefore($hoy) || $hoy->equalTo($p)){
                    $contador += 1;
                }else{
                    $band= false;
                }
            }
            
        }
        if($suscripcion['periodo'] == 'Semanal'){
            $contador = $prox_cob->diffInWeeks($hoy);
        }
        if($suscripcion['periodo'] == 'Mensual'){
            $contador = $prox_cob->diffInMonths($hoy);
        }
        if($suscripcion['periodo'] == 'Anual'){
            $contador = $prox_cob->diffInYears($hoy);
        }

        return $contador;
        
    }
}
