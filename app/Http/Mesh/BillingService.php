<?php


namespace App\Http\Mesh;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BillingService extends ServicesMesh
{
    public function __construct()
    {
        parent::__construct(env('BILLING_API'));
    }


    /**
     * @param $id
     * @return null[]
     */
    public function consultarSuscripciones($id,$fecha)
    {
        try {
            $endpoint = env('BILLING_API').'/historial/'.$id.'?fecha='.$fecha;
            $options = $this->getOptions($this->getHeaders($this->getRequest()));
            $response = $this->client->get($endpoint, $options);

            if ($response->getStatusCode() !== 200){
                Log::critical($response->getStatusCode() . ":   " .  $response->getBody());
                return [];
            }

            $client = json_decode($response->getBody(),true);

            return $client;

        }catch (Exception $exception){
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return [];
        }


    }

    public function generarFacturas($json)
    {
        try {
            $endpoint = env('BILLING_API').'/factura/suscripcion';
            $response = $this->client->post($endpoint, $json);

            if ($response->getStatusCode() !== 200){
                Log::critical($response->getStatusCode() . ":   " .  $response->getBody());
                return [];
            }

            $client = json_decode($response->getBody(),true);

            return $client;

        }catch (Exception $exception){
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return [$exception->getMessage()];
        }


    }
}