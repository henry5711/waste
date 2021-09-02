<?php

/*
|--------------------------------------------------------------------------
| Application $router->|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Router;

/*
* ALL THE METHODS WITH A _ BEFORE THOSE NAME GOES DIRECTLY TO REPOSITORY THROUGH TATUCO METHODS
* TODOS LOS METODOS CON UN _ EN EL PREFIJO DEL NOMBRE VAN DIRECTAMENTE AL REPOSITORIO POR MEDIO DE LOS METODOS DE TATUCO
*/

$router->group(['prefix' => 'api'], function (Router $router) {

    $router->get('/', function () use ($router) {

        return response()->json([
            "version"=> $router->app->version(),
            "time"   => Carbon::now()->toDateTime(),
            "php"    =>  phpversion()
        ]);
    });
    
    /*
     *routes with report prefix
     * rutas con el prefijo report
    */
    $router->group(['prefix' => 'report'], function () use ($router) {
        $router->post('/automatic', 'ReportController@automatic');

    });
    
    $router->group(['middleware' => ['auth']],function () use ($router) {

        /** routes para planes **/ 
    $router->get('planes/tipo', 'planes\planesController@planetipo');
    $router->get('planes', 'planes\planesController@_index');
    $router->get('planes/{id}', 'planes\planesController@_show');
    $router->post('planes/p/prueba', 'planes\planesController@guardar');
    $router->put('planes/{id}', 'planes\planesController@_update');
    $router->delete('planes/{id}', 'planes\planesController@_delete');


    /** routes para suscripciones **/ 
       
   $router->get('suscripciones', 'suscripciones\suscripcionesController@_index');
   $router->get('suscripciones/{id}', 'suscripciones\suscripcionesController@_show');
   $router->post('suscripciones', 'suscripciones\suscripcionesController@_store');
   $router->put('suscripciones/{id}', 'suscripciones\suscripcionesController@_update');
   $router->delete('suscripciones/{id}', 'suscripciones\suscripcionesController@_delete');
    

    $router->group(['middleware' => ['authorize']],function () use ($router) {

        $router->group(['namespace' => '\Rap2hpoutre\LaravelLogViewer'], function() use ($router) {
            $router->get('logs', 'LogViewerController@index');
        });

    });
});

    
    
 

    
});

