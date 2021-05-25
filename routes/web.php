<?php

use App\Http\Controllers\TiposActividadesController;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\ReporteController;
=======
use App\Http\Controllers\ActividadesController;
>>>>>>> dev
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

/*
Route::get('/', function () {
    return view('layout/layout');
});
<<<<<<< HEAD
Route::get('/reporte', [Reportecontroller::class, 'reporte']);

Route::get('Detalles/{id}', [Reportecontroller::class, 'Detalles'])->name('Detalles');

=======
*/

Route::resource('admin/tipos-actividades', TiposActividadesController::class, ['names' => 'tipos-actividades']);

////Actividades
Route::get('reporte_actividades', [ActividadesController::class,'reporte_actividades'])->name('reporte_actividades');
Route::get('actividades', [ActividadesController::class,'actividades'])->name('create_actividades');
Route::get('tipousuarios', [ActividadesController::class,'tipousuarios'])->name('ajax_tipousuarios');
Route::POST('insert_actividad', [ActividadesController::class,'insert_actividad'])->name('insert_actividad');
Route::get('actividades_modificacion/{id}', [ActividadesController::class,'actividades_modificacion'])->name('edit_modificacion');
>>>>>>> dev
