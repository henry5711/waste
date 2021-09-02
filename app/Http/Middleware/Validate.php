<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 28/01/19
 * Time: 02:40 PM
 */

namespace App\Http\Middleware;


use Closure;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class Validate
{
    protected $client;

    public function __construct()
    {
        $this->client= new Client(['base_uri' => env('USERS_API')]);
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $header = [
            "Authorization" => $request->header('Authorization'),
            "Accept" => "application/json",
            "Cache-Control" => "no-cache"
        ];

        $response = $this->client->get('validate',['headers' => $header]);

        if($response->getStatusCode() == 401 OR $response->getStatusCode() == 403){
            return response()->json(["error"=>true,"message"=>"unauthorized"],$response->getStatusCode());
        }
        if($response->getStatusCode() !== 200){
            return response()->json(["error"=>true,"message"=>"Internal Error"],$response->getStatusCode());
        }

        return  $next($request);
    }
}