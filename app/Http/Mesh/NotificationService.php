<?php


namespace App\Http\Mesh;


use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class NotificationService extends ServicesMesh
{
    public function __construct()
    {
        parent::__construct(env('NOTIFICATION_API','https://devrubick2notifications.zippyttech.com'));
    }

    /**
     * Muestra todos los clientes
     *
     * @return array
     */
     public function enviar($data){
        try {
            // $client = new Client();
            $option = [
                'header'    => $this->getHeaders($this->getRequest()),
                'json'      => $data
            ];
            $response = $this->client->post('/nt/send/email/exception',$option);

            if ($response->getStatusCode() !== 200){
                Log::critical($response->getStatusCode() . ":   " .  $response->getBody());
                return [];
            }

            $account = json_decode($response->getBody(),true);

            return $account['list'] ?? [];

        }catch (Exception $exception){
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return [];
        }
     }
     
}