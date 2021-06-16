<?php

use App\Http\Controllers\SeguimientoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\Sistema\Panel\PanelController;

use App\Http\Controllers\AreasController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TiposActividadesController;

use App\Http\Controllers\EncryptController;

Auth::routes();
/*
 * Cambiar Auth::routes() por rutas a solo usar, 
 * ya que la ruta /register permite inserción de usuarios.
 */

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/panel', [PanelController::class,'panel']);
Route::get('/panel/get-actividades-hoy/{idu}', [PanelController::class,'getActividadesHoy']);
Route::get('/panel/get-actividades-pendientes/{idu}', [PanelController::class,'getActividadesPendientes']);
Route::get('/panel/get-actividades-por-mes/{idu}', [PanelController::class,'getActividadesPorMes']);
Route::get('/panel/get-actividades-cerradas/{idu}', [PanelController::class,'getActividadesCerradas']);
Route::get('/panel/get-actividades-en-seguimiento/{idu}', [PanelController::class,'getActividadesEnSeguimiento']);




Route::resource('tipos-actividades', TiposActividadesController::class, ['names' => 'tipos-actividades']);
Route::resource('users', UsersController::class, ['names' => 'users']);
Route::resource('areas', AreasController::class, ['names' => 'areas']);

//Seguimiento de actividades

Route::get('actividades_asignadas', [SeguimientoController::class,'actividades_asignadas'])->name('actividades_asignadas');
Route::get('DetallesAsignacion/{idac}', [SeguimientoController::class, 'DetallesAsignacion'])->name('DetallesAsignacion');
Route::get('Seguimiento/{idac}', [SeguimientoController::class, 'Seguimiento'])->name('Seguimiento');
Route::POST('AgregarSeguimiento', [SeguimientoController::class,'AgregarSeguimiento'])->name('AgregarSeguimiento');
Route::get('EliminarSeguimiento/{idarse}/{idseac}', [SeguimientoController::class, 'EliminarSeguimiento'])->name('EliminarSeguimiento');
Route::get('DetallesArchivos/{idarc}', [SeguimientoController::class, 'DetallesArchivos'])->name('DetallesArchivos');

Route::post('aceptarActividad', [SeguimientoController::class,'aceptarActividad'])->name('aceptarActividad');
Route::post('rechazarActividad', [SeguimientoController::class,'rechazarActividad'])->name('rechazarActividad');


////Actividades

Route::get('reporte_actividades', [ActividadesController::class,'reporte_actividades'])->name('reporte_actividades');
Route::get('activacion/{id}/{activo}', [ActividadesController::class,'activacion'])->name('activacion');
Route::get('actividades', [ActividadesController::class,'actividades'])->name('create_actividades');
Route::get('tipousuarios', [ActividadesController::class,'tipousuarios'])->name('ajax_tipousuarios');
Route::POST('insert_actividad', [ActividadesController::class,'insert_actividad'])->name('insert_actividad');
Route::get('actividades_modificacion/{id}', [ActividadesController::class,'actividades_modificacion'])->name('edit_modificacion');

Route::get('Detalles/{id}', [ActividadesController::class, 'Detalles'])->name('Detalles');
Route::get('detallesSeguimiento/{idac}', [ActividadesController::class, 'detallesSeguimiento'])->name('detallesSeguimiento');
Route::get('DetallesArchivos/{idarseg}', [ActividadesController::class, 'DetallesArchivos'])->name('DetallesArchivos');
/* Generación PDF */
Route::get('pdf/{idac}', [ActividadesController::class, 'pdf'])->name('pdf');
/* Fin de generación PDF */
Route::POST('update_actividades', [ActividadesController::class,'update_actividades'])->name('update_actividades');

Route::resource('admin/areas', AreasController::class, ['names' => 'areas']);
Route::resource('admin/users', UsersController::class, ['names' => 'users']);

Route::get('actividades_creadas/{id}', [ActividadesController::class, 'actividades_creadas'])->name('actividades_creadas');


//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
/*
Route::get('hello',function(){
    return view('hello');
});*/
Route::get('hello', [EncryptController::class,'index']);

