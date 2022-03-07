<?php


namespace App\Http\Mesh;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientService extends ServicesMesh
{
    public function __construct()
    {
        parent::__construct(env('CLIENT_API'));
    }

    /**
     * @param $id
     * @return null[]
     */
    public function getSucursalClient($id): array
    {
        try {
            $endpoint = '/api/branch/client';
            
            $option = [
                'header'    => $this->getHeaders($this->getRequest()),
                'json'      => ['branches'=>$id]
            ];
            $response = $this->client->post($endpoint, $option);

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
}