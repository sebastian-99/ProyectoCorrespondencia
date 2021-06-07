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


Auth::routes();
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/panel', [PanelController::class,'panel']);
Route::get('/', function () {
    return view('auth.login');
});


Route::resource('tipos-actividades', TiposActividadesController::class, ['names' => 'tipos-actividades']);
Route::resource('users', UsersController::class, ['names' => 'users']);
Route::resource('areas', AreasController::class, ['names' => 'areas']);

// Seguimiento de actividades
Route::get('actividades_asignadas', [SeguimientoController::class,'actividades_asignadas'])->name('actividades_asignadas');
Route::get('Seguimiento/{idac}', [SeguimientoController::class, 'Seguimiento'])->name('Seguimiento');
Route::POST('AgregarSeguimiento', [SeguimientoController::class,'AgregarSeguimiento'])->name('AgregarSeguimiento');
Route::get('EliminarSeguimiento/{idarse}', [SeguimientoController::class, 'EliminarSeguimiento'])->name('EliminarSeguimiento');



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

Route::POST('update_actividades', [ActividadesController::class,'update_actividades'])->name('update_actividades');

Route::get('actividades_creadas/{id}', [ActividadesController::class, 'actividades_creadas'])->name('actividades_creadas');

Route::get('actividades_asignadas/{id}', [ActividadesController::class, 'actividades_asignadas'])->name('actividades_asignadas');

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
