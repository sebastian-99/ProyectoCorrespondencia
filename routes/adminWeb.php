<?php

use App\Http\Controllers\Graficas\AdminGraficasController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard',[AdminGraficasController::class,'dashboard']);

Route::post('/dashboard',[AdminGraficasController::class,'getEstadisticasDeActividades']);

Route::post('/dashboard/get-actividades-completadas', [AdminGraficasController::class,'actividadesCompletadas']);
Route::post('/dashboard/get-actividades-en-proceso', [AdminGraficasController::class,'actividadesEnProceso']);
Route::post('/dashboard/get-actividades-sin-entregar', [AdminGraficasController::class,'actividadesSinEntregar']);
Route::post('/dashboard/get-actividades-con-acuse-de-recibido', [AdminGraficasController::class,'actividadesConAcuseDeRecibido']);
Route::post('/dashboard/get-actividades-sin-acuse-de-recibido', [AdminGraficasController::class,'actividadesSinAcuseDeRecibido']);

Route::post('/dashboard/actividades-completadas-en-tiempo', [AdminGraficasController::class,'actividadesCompletadasEnTiempo'])->name('actividades.en-tiempo');
Route::post('/dashboard/actividades-completadas-fuera-de-tiempo', [AdminGraficasController::class,'actividadesCompletadasFueraDeTiempo']);
Route::post('/dashboard/actividades-en-proceso-en-tiempo', [AdminGraficasController::class,'actividadesEnProcesoEnTiempo']);
Route::post('/dashboard/actividades-en-proceso-fuera-de-tiempo', [AdminGraficasController::class,'actividadesEnProcesoFueraDeTiempo']);


Route::post('/dashboard/get-actividades-por-area', [AdminGraficasController::class,'getActividadesPorTipoArea']);
Route::get('/seguimiento/{idac}', [AdminGraficasController::class,'seguimiento']);
