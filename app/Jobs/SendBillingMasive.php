<?php

namespace App\Jobs;

use App\Http\Mesh\BillingService;
use App\Models\HistorialBillingMasive;
use App\Models\suscripciones;
use App\Services\suscripciones\suscripcionesService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBillingMasive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $suscripciones;
    protected $json;
    protected $service;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $suscripciones, $json, suscripcionesService $service)
    {
        $this->suscripciones = $suscripciones;
        $this->json = $json;
        $this->service = $service;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Log::info('Enviando facturacion masiva...');
        foreach ($this->suscripciones as $suscripcion) {
            $suscrip = suscripciones::find($suscripcion);
            $expected_quantity = $this->service->cantidadFacturas([$suscrip]);
            $historial = HistorialBillingMasive::where('suscripcion_id',$suscrip->id)
                            ->where('status','En Proceso')
                            ->first();
            if($historial == null || $historial == ''){
                $suscrip->historialBillingMasive()->create([
                    'expected_quantity' => $expected_quantity
                ]);
            }else{
                $historial->expected_quantity = $expected_quantity;
                $historial->save();
            }
        }
        // return $json;
        $client=new BillingService;
        $client->generarFacturas($this->json);
    }

    
}
