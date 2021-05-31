<?php

use App\Http\Controllers\TiposActividadesController;
use App\Http\Controllers\SeguimientoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActividadesController;
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



Route::resource('admin/tipos-actividades', TiposActividadesController::class, ['names' => 'tipos-actividades']);


// Seguimiento de actividades 
Route::get('Seguimiento/{idac}', [SeguimientoController::class, 'Seguimiento'])->name('Seguimiento');
Route::get('archivo/{idac}/download', [SeguimientoController::class, 'Descarga'])->name('archivo.descarga');
Route::POST('AgregarSeguimiento', [SeguimientoController::class,'AgregarSeguimiento'])->name('AgregarSeguimiento');

////Actividades
Route::get('reporte_actividades', [ActividadesController::class,'reporte_actividades'])->name('reporte_actividades');
Route::get('activacion/{id}/{activo}', [ActividadesController::class,'activacion'])->name('activacion');
Route::get('actividades', [ActividadesController::class,'actividades'])->name('create_actividades');
Route::get('tipousuarios', [ActividadesController::class,'tipousuarios'])->name('ajax_tipousuarios');
Route::POST('insert_actividad', [ActividadesController::class,'insert_actividad'])->name('insert_actividad');
Route::get('actividades_modificacion/{id}', [ActividadesController::class,'actividades_modificacion'])->name('edit_modificacion');
Route::POST('update_actividades', [ActividadesController::class,'update_actividades'])->name('update_actividades');
