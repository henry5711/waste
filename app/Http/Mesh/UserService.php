<?php


namespace App\Http\Mesh;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Log;

class UserService extends ServicesMesh
{

    public function __construct()
    {
        parent::__construct(env('USERS_API'));
    }

    /**
     * @param $id
     * @return null[]
     */
    public function getAllUsers($id): array
    {
        try {
            $options = $this->getOptions($this->getHeaders($this->getRequest()));
            $response = $this->client->get('/users/'.$id, $options);

            if ($response->getStatusCode() !== 200){
                Log::critical($response->getStatusCode() . ":   " .  $response->getBody());
                return [];
            }

            $client = json_decode($response->getBody(),true);

            return $client['list'] ?? [];

        }catch (Exception $exception){
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return [];
        }


    }

    /**
     * Returns a Client from API-Customers, by id
     * @param $username
     * @return array
     */
    public function getUserByUsername($username): array
    {
        $endpoint = '/users/'.$username;

        try {
            $options = $this->getOptions($this->getHeaders($this->getRequest()));
            $response = $this->client->get($endpoint, $options);

            if ($response->getStatusCode() !== 200) {
                Log::critical($response->getStatusCode() . ":   " . $response->getBody());
                return ["id" => null];
            }

            $client = json_decode($response->getBody(), true);

            return $client['Usuario'] ?? ["id" => null];

        } catch (Exception $exception) {
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return ["id" => null];
        }
    }

    /**
     * Returns a Client from API-Customers, by id
     * @param array $ids
     * @return array
     */
    public function getUsersById(array $ids): array
    {
        $uri = '/users?where=[{"op":"in","field":"users.id","value":'.json_encode($ids).'}]';

        try {
            $options = $this->getOptions($this->getHeaders($this->getRequest()));
            $response = $this->client->get($uri, $options);

            if ($response->getStatusCode() !== 200){
                Log::critical($response->getStatusCode() . ":   " .  $response->getBody());
                return [];
            }

            $client = json_decode($response->getBody(),true);

            return $client['list'] ?? [];

        }catch (Exception $exception){
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return [];
        }
    }

    /**
     * Returns a Client from API-Customers, by id
     * @param $id
     * @return array
     */
    public function getUserById($id)
    {
        $uri = '/users?where=[{"op":"eq","field":"users.id","value":' . $id . '}]';

        try {
            $options = $this->getOptions($this->getHeaders($this->getRequest()));
            $response = $this->client->get($uri, $options);

            if ($response->getStatusCode() !== 200) {
                Log::critical($response->getStatusCode() . ":   " . $response->getBody());
                return ["id" => null];
            }

            $client = json_decode($response->getBody(), true);

            return $client['Usuario'] ?? ["id" => null];

        } catch (Exception $exception) {
            Log::critical($exception->getMessage());
            Log::critical($exception->getFile());

            return ["id" => null];
        }
    }

}
