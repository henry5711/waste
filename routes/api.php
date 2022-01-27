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

use App\Http\Controllers\suscripciones\suscripcionesController;
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
    $router->post('planes/guardar/datos', 'planes\planesController@guardar');
    $router->put('planes/{id}', 'planes\planesController@_update');
    $router->delete('planes/{id}', 'planes\planesController@_delete');


    /** routes para suscripciones **/ 
    $router->post('suscripciones/facturar/activas', 'suscripciones\suscripcionesController@facturar');
    $router->get('mostrar/facturas/suscripciones', 'suscripciones\suscripcionesController@mostrarFacturas');
    $router->post('suscripciones/edit/proximo', 'suscripciones\suscripcionesController@editarproxifecha');
    $router->get('suscripciones/usuario', 'suscripciones\suscripcionesController@usuariosus');
    $router->get('suscripciones/{id}', 'suscripciones\suscripcionesController@_show');
    $router->post('suscripciones', 'suscripciones\suscripcionesController@_store');
    $router->put('suscripciones/{id}', 'suscripciones\suscripcionesController@_update');
    $router->delete('suscripciones/{id}', 'suscripciones\suscripcionesController@_delete');
    $router->get('suscripciones', 'suscripciones\suscripcionesController@_index');
    $router->get('buscar/cliente/suscripciones/{id_client}','suscripciones\suscripcionesController@buscarClienteId');
    $router->get('numero/suscripcion','suscripciones\suscripcionesController@generarNumero');
    $router->get('filtro/cliente/suscripcion/{id_suscripcion}','suscripciones\suscripcionesController@buscarClienteFiltro');
    $router->get('filtro/suscripcion','suscripciones\suscripcionesController@Filtro');
    /**
    * Agregado por Marcos López
    */
    $router->get('detalle/suscripciones/{id_suscripcion}', 'suscripciones\suscripcionesController@verDetalle');
    $router->post('estado/suscripciones','suscripciones\suscripcionesController@estado');
    /**----------------------------------------- */


    /** routes para prodetalle **/ 
    $router->get('prodetalles/suscripcion', 'prodetalle\prodetalleController@detallesus');
    $router->get('prodetalles', 'prodetalle\prodetalleController@_index');
    $router->get('prodetalles/{id}', 'prodetalle\prodetalleController@_show');
    $router->post('prodetalles', 'prodetalle\prodetalleController@_store');
    $router->put('prodetalles/{id}', 'prodetalle\prodetalleController@_update');
    $router->delete('prodetalles/{id}', 'prodetalle\prodetalleController@_delete');
 
    /** routes para Clientes **/ 

    $router->get('clientes', 'Clientes\ClientesController@_index');
    $router->get('clientes/{id}', 'Clientes\ClientesController@_show');
    $router->post('clientes', 'Clientes\ClientesController@_store');
    $router->put('clientes/{id}', 'Clientes\ClientesController@_update');
    $router->delete('clientes/{id}', 'Clientes\ClientesController@_destroy');

    /** routes para operation **/ 
 
    $router->get('operations', 'operation\operationController@_index');
    $router->get('operations/creadas', 'operation\operationController@icreadas');
    $router->get('operations/{id}', 'operation\operationController@_show');
    $router->post('operations', 'operation\operationController@_store');
    $router->put('operations/{id}', 'operation\operationController@_update');
    $router->delete('operations/{id}', 'operation\operationController@_delete');

    /** routes para rutas **/ 
 
    $router->get('rutas', 'rutas\rutasController@_index');
    $router->get('rutas/all/{id}', 'rutas\rutasController@showrut');
    $router->get('rutas/{id}', 'rutas\rutasController@_show');
    $router->post('rutas', 'rutas\rutasController@_store');
    $router->put('rutas/{id}', 'rutas\rutasController@_update');
    $router->delete('rutas/{id}', 'rutas\rutasController@_delete');

    /** routes para config **/ 
 
    $router->get('configs', 'config\configController@_index');
    $router->get('configs/{id}', 'config\configController@_show');
    $router->post('configs', 'config\configController@_store');
    $router->put('configs/{id}', 'config\configController@_update');
    $router->delete('configs/{id}', 'config\configController@_delete');


    $router->group(['middleware' => ['authorize']],function () use ($router) {

        $router->group(['namespace' => '\Rap2hpoutre\LaravelLogViewer'], function() use ($router) {
            $router->get('logs', 'LogViewerController@index');
        });

    });

});

    
    
 

    
});

 
 
 
/** routes para config **/ 
 
$router->get('configs', 'config\configController@_index');
$router->get('configs/{id}', 'config\configController@_show');
$router->post('configs', 'config\configController@_store');
$router->put('configs/{id}', 'config\configController@_update');
$router->delete('configs/{id}', 'config\configController@_delete');
