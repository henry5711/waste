<?php


namespace App\Http\Mesh;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientService extends ServicesMesh
{
    public function __construct()
    {
        parent::__construct($this->getRouteCustomer());
    }

    /**
     * @param $id
     * @return null[]
     */
    public function getClient($id): array
    {
        $request = Request::capture();
        $options = [
            "headers" =>  $this->getHeaders($request)
        ];
        try {
            $response = $this->client->get('/cs/clients/'.$id, $options);

            if ($response->getStatusCode() !== 200){
                Log::critical($response->getStatusCode() . ":   " .  $response->getBody());
                return ["id"=> null];
            }

            $client = json_decode($response->getBody(),true);

            return $client['Cliente'] ?? ["id"=> null];

        }catch (\Exception $exception){
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return ["id"=> null];
        }


    }
}