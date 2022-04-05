<?php

use App\Http\Controllers\Clientes\ClientesController;
use App\Http\Controllers\config\configController;
use App\Http\Controllers\operation\operationController;
use App\Http\Controllers\planes\planesController;
use App\Http\Controllers\prodetalle\prodetalleController;
use App\Http\Controllers\rutas\rutasController;
use App\Http\Controllers\suscripciones\suscripcionesController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/', function () {

    return response()->json([
        //"version" => Route::app->version(),
        "time"   => Carbon::now()->toDateTime(),
        "php"    =>  phpversion()
    ]);
});

/*
     *routes with report prefix
     * rutas con el prefijo report
    */
// Route::prefix('report')->group(function () {
//     Route::post('/automatic', 'ReportController@automatic']);
// });

// Route::middleware('auth')->group(function () {

/** routes para planes **/
Route::get('planes/tipo', [planesController::class, 'planetipo']);
Route::get('planes', [planesController::class, '_index']);
Route::get('planes/{id}', [planesController::class, '_show']);
Route::post('planes/guardar/datos', [planesController::class, 'guardar']);
Route::put('planes/{id}', [planesController::class, '_update']);
Route::delete('planes/{id}', [planesController::class, '_delete']);

/**
 * Agregado por Marcos López
 */
/** routes para suscripciones **/
Route::post('suscripciones/facturar/activas', [suscripcionesController::class, 'facturar']);
Route::get('mostrar/facturas/suscripciones', [suscripcionesController::class, 'mostrarFacturas']);
Route::post('suscripciones/edit/proximo', [suscripcionesController::class, 'editarproxifecha']);
Route::get('suscripciones/usuario', [suscripcionesController::class, 'usuariosus']);
Route::get('suscripciones/{id}', [suscripcionesController::class, '_show']);
Route::post('suscripciones', [suscripcionesController::class, '_store']);
Route::post('calcular_facturas/suscripciones', [suscripcionesController::class, 'calcularFacturas']);
Route::get('detalle_facturas/suscripciones', [suscripcionesController::class, 'detalleSuscripcionParaFacturar']);
Route::put('suscripciones/{id}', [suscripcionesController::class, '_update']);
Route::delete('suscripciones/{id}', [suscripcionesController::class, '_delete']);
Route::get('suscripciones', [suscripcionesController::class, '_index']);
Route::get('buscar/cliente/suscripciones/{id_client}', [suscripcionesController::class, 'buscarClienteId']);
Route::get('numero/suscripcion', [suscripcionesController::class, 'generarNumero']);
Route::get('filtro/cliente/suscripcion/{id_suscripcion}', [suscripcionesController::class, 'buscarClienteFiltro']);
Route::get('filtro/suscripcion', [suscripcionesController::class, 'Filtro']);

Route::post('suscripciones/generate_operations', [suscripcionesController::class, 'generateOperations']);
Route::post('calcular_operaciones/suscripciones', [suscripcionesController::class, 'calculateOperations']);
Route::get('detalle_operaciones/suscripciones', [suscripcionesController::class, 'detailSuscriptionForOperation']);
Route::get('detalle/suscripciones/{id_suscripcion}', [suscripcionesController::class, 'verDetalle']);
Route::post('estado/suscripciones', [suscripcionesController::class, 'estado']);
/**----------------------------------------- */


/** routes para prodetalle **/
Route::get('prodetalles/suscripcion', [prodetalleController::class, 'detallesus']);
Route::get('prodetalles', [prodetalleController::class, '_index']);
Route::get('prodetalles/{id}', [prodetalleController::class, '_show']);
Route::post('prodetalles', [prodetalleController::class, '_store']);
Route::put('prodetalles/{id}', [prodetalleController::class, '_update']);
Route::delete('prodetalles/{id}', [prodetalleController::class, '_delete']);

/** routes para Clientes **/

Route::get('clientes', [ClientesController::class, '_index']);
Route::get('clientes/{id}', [ClientesController::class, '_show']);
Route::post('clientes', [ClientesController::class, '_store']);
Route::put('clientes/{id}', [ClientesController::class, '_update']);
Route::delete('clientes/{id}', [ClientesController::class, '_destroy']);

/** routes para operation **/

Route::get('operations/filter/range', [operationController::class, 'filtro']);
Route::get('sucursales/total', [operationController::class, 'sucurconsulta']);
Route::get('operations', [operationController::class, '_index']);
Route::get('operations/creadas', [operationController::class, 'icreadas']);
Route::get('operations/filter/report', [operationController::class, 'reportope']);
Route::get('operations/sucursal/mes/report', [operationController::class, 'repodias']);
Route::get('operations/{id}', [operationController::class, '_show']);
Route::post('operations', [operationController::class, '_store']);
Route::put('operations/{id}', [operationController::class, '_update']);
Route::delete('operations/{id}', [operationController::class, '_delete']);

/** routes para rutas **/

Route::get('rutas/filtro', [rutasController::class, 'filtro']);
Route::get('rutas/filtro/report', [rutasController::class, 'repofil']);
Route::get('rutas', [rutasController::class, '_index']);
Route::get('rutas/all/{id}', [rutasController::class, 'showrut']);
Route::get('rutas/{id}', [rutasController::class, '_show']);
Route::post('rutas', [rutasController::class, '_store']);
Route::put('rutas/{id}', [rutasController::class, '_update']);
Route::delete('rutas/{id}', [rutasController::class, '_delete']);

/** routes para config **/

Route::get('configs', [configController::class, '_index']);
Route::get('configs/{id}', [configController::class, '_show']);
Route::post('configs', [configController::class, '_store']);
Route::put('configs/{id}', [configController::class, '_update']);
Route::delete('configs/{id}', [configController::class, '_delete']);


Route::middleware('authorize')->group(function () {

    // Route::namespace('\Rap2hpoutre\LaravelLogViewer'], function(){
    //     Route::get('logs', 'LogViewerController@index']);
    // });
});
// });




/** routes para config **/

Route::get('configs', [configController::class, '_index']);
Route::get('configs/{id}', [configController::class, '_show']);
Route::post('configs', [configController::class, '_store']);
Route::put('configs/{id}', [configController::class, '_update']);
Route::delete('configs/{id}', [configController::class, '_delete']);
/** routes para Acceso **/

Route::get('accesos', [\App\Http\Controllers\Acceso\AccesoController::class, '_index']);
Route::get('accesos/{id}', [\App\Http\Controllers\Acceso\AccesoController::class, '_show']);
Route::post('accesos', [\App\Http\Controllers\Acceso\AccesoController::class, '_store']);
Route::put('accesos/{id}', [\App\Http\Controllers\Acceso\AccesoController::class, '_update']);
Route::delete('accesos/{id}', [\App\Http\Controllers\Acceso\AccesoController::class, 'delete']);