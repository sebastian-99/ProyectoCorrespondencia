<?php

use App\Http\Controllers\Sistema\Actividades\ActividadesController;
use App\Http\Controllers\Sistema\Panel\PanelController;
use Illuminate\Support\Facades\Route;

Route::get('/actividades/dashboard',[ActividadesController::class,'dashboard'])->name('actividades.dashboard');
Route::get('/getAreasPorTipoArea/{tipoArea}',[ActividadesController::class,'getAreasPorTipoArea'])->name('get.areas.por.tipo.area');
Route::get('/get-actividades-ṕor-mes/{areas}/{year}/{mes}',[ActividadesController::class,'getActividadesṔorMes']);
Route::get('/get-actividades-ṕor-rango-de-fechas/{areas}/{inicio}/{fin}',[ActividadesController::class,'getActividadesṔorRangoDeFechas']);
Route::get('/get-actividades-totales/{areas}',[ActividadesController::class,'getActividadesTotales']);
Route::get('/seguimiento/{idac}', [ActividadesController::class,'seguimiento']);

Route::get('/get-actividades-completadas-por-mes/{areas}/{mes}/{year}',[ActividadesController::class,'getActividadesCompletadasPorMes']);
Route::get('/get-actividades-en-proceso-por-mes/{areas}/{mes}/{year}',[ActividadesController::class,'getActividadesEnProcesoPorMes']);
Route::get('/get-actividades-sin-entregar-por-mes/{areas}/{mes}/{year}',[ActividadesController::class,'getActividadesSinEntregarPorMes']);

Route::get('/get-actividades-completadas/{areas}/{inicio}/{fin}/{year}',[ActividadesController::class,'getActividadesCompletadas']);
Route::get('/get-actividades-en-proceso/{areas}/{inicio}/{fin}/{year}',[ActividadesController::class,'getActividadesEnProceso']);
Route::get('/get-actividades-sin-entregar/{areas}/{inicio}/{fin}/{year}',[ActividadesController::class,'getActividadesSinEntregar']);

Route::get('/panel', [PanelController::class,'panel']);
