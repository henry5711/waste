<?php


namespace App\Http\Mesh;

use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryService extends ServicesMesh
{
    public function __construct()
    {
        parent::__construct(env('INVENTORY_API'));
    }

    /**
     * @param $id
     * @return null[]
     */
    public function guardarProducto($prod)
    {
        $faker = Factory::create();
        $codigo = $faker->regexify('[A-Z]{5}[0-4]{3}');
        $json = [
            "code"              => $codigo,
            "name"              => $prod['nom_pro'],
            "type_description"  => "",
            "category_name"     => "",
            "sale_price"        => $prod['precio'],
            "account"           => 1,
            "term"              => [],
            "detail"            => []            
        ];
        // return $json;

        $headers = $this->getHeaders($this->getRequest());
        $options = [
            'headers' => $headers,
            'json' => $json
        ];
        
        try {
            $response = $this->client->post('/api/product', $options);

            if ($response->getStatusCode() !== 201){
                Log::critical($response->getStatusCode() . ":   " .  $response->getBody());
                return ["id"=> null];
            }else{
                // return true;
                $client = json_decode($response->getBody(),true);
    
                return $client['product'];
            }


        }catch (\Exception $exception){
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return [$exception->getMessage()];
        }


    }
}