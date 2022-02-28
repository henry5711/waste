<?php

namespace App\Jobs;

use App\Http\Controllers\Factura\ImpresionFiscalController;
use App\Http\Controllers\operation\operationController;
use App\Http\Controllers\suscripciones\suscripcionesController;
use App\Http\Mesh\SuscripcionService;
use App\Models\detalleFactura;
use App\Models\Factura;
use App\Models\operation;
use App\Models\Suscripcion;
use App\Models\suscripciones;
use App\Repositories\operation\operationRepository;
use App\Repositories\suscripciones\suscripcionesRepository;
use App\Services\operation\operationService;
use App\Services\suscripciones\suscripcionesService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use soapclient;

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
    

    public function __construct(suscripciones $suscripcion = null)
    {
       // $this->susController = new suscripcionesController(new suscripcionesService(new suscripcionesRepository(new suscripciones())));
        
        $this->suscripciones  = ( $suscripcion !== null) ? $suscripcion : suscripcionesController::getGenerateOperations(new Request(['all'=>true]));
        dd($this->suscripciones);
        $this->operation    = new operationController(new operationService(new operationRepository(new operation())));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $store = [];
        
        $store = [
            'ids'           => $this->suscripcion->sucursal_id,
            'name_sucursal' => $this->suscripcion->name_sucursal,
            'coordenada'    => $this->suscripcion->coordenada_sucursal,
            'fecha'         => $this->date,
            'tipo'          => 'web',
            'usu/cli'       => 'cliente'
        ];

        $this->operation->_store(new Request($store));

    }
}
