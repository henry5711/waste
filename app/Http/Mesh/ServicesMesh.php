<?php


namespace App\Http\Mesh;


use App\Http\Services\ConsumesExternalService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ServicesMesh
{

  //  use ConsumesExternalService;

    public $client;

    private $baseUrl;

    private $request;


    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->request = Request::capture();
        $this->client  = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 7,
        ]);
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @param array $headers
     * @return array[]
     */
    public function getOptions(array $headers): array
    {
        return [
            "headers" => $headers
        ];
    }

    /**
     * @param $request
     * @return array
     */
    public function getHeaders($request): array
    {
        $token = '';
        if($request->hasHeader('Authorization')){
            $token = $request->header('Authorization');
        }
        if ($request->has('token')){
            $token = 'Bearer ' .$request->input('token');
        }
        $timezone = $request->hasHeader('x-timezone') ? $request->header('x-timezone') : "UTC,+0:00";
        return [
            "Authorization" => $token,
            "Accept"        => "application/json",
            "Cache-Control" => "no-cache",
            "x-timezone"    => $timezone
        ];
    }
}