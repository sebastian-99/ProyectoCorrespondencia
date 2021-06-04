<?php

use App\Http\Controllers\Sistema\Actividades\ActividadesController;
use Illuminate\Support\Facades\Route;

Route::get('/actividades/dashboard',[ActividadesController::class,'dashboard'])->name('actividades.dashboard');
Route::get('/getAreasPorTipoArea/{tipoArea}',[ActividadesController::class,'getAreasPorTipoArea'])->name('get.areas.por.tipo.area');
Route::get('/get-actividades-ṕor-mes/{areas}/{year}/{mes}',[ActividadesController::class,'getActividadesṔorMes']);
Route::get('/get-actividades-ṕor-rango-de-fechas/{areas}/{inicio}/{fin}',[ActividadesController::class,'getActividadesṔorRangoDeFechas']);
