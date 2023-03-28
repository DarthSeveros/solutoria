<?php

use App\Http\Controllers\IndicadoresApi;
use App\Http\Controllers\IndiceFinancieroController;
use App\Http\Controllers\SolicitudesController;
use App\Models\IndiceFinanciero;
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

Route::controller(SolicitudesController::class)->prefix('solicitud')->group(function() {
    Route::get('/', 'index')->name('indices.index');
    Route::get('/lista/{cantidad?}', 'getIndicadores')->name('solicitud.lista');
    Route::post('/create', 'create')->name('solicitud.create');
    Route::delete('/delete/{id}', 'delete')->name('solicitud.delete');
    Route::patch('/update/{id}', 'update')->name('solicitud.update');
    Route::get('/data','getIndicadoresData');
    Route::get('data/fecha', 'getIndicadoresByFecha');
});

Route::controller(IndicadoresApi::class)->group(function(){
    Route::get('indicadoresFromApi/actualizar', 'update')->name('indicadoresFromApi.update');
});
