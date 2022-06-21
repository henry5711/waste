<?php

use App\Events\CheckHistorialBillingMasive;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('historial-billing-masive',function(){
    App\Events\CheckHistorialBillingMasive::dispatch('Hola');
    //event(new CheckHistorialBillingMasive('asd'));
    return 'api';
});