<?php

use App\Http\Controllers\TiposActividadesController;
use App\Http\Controllers\SeguimientoController;
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

Route::get('plantilla', function () {
    return view('layout/layout');
});

Route::resource('admin/tipos-actividades', TiposActividadesController::class, ['names' => 'tipos-actividades']);


// Seguimiento de actividades 
Route::get('Seguimiento/{idac}', [SeguimientoController::class, 'Seguimiento'])->name('Seguimiento');

//Route::name('Seguimiento')->get('alumnos', [AlumnosController::class, 'index']);
//Route::get('modificaDoctor/{id_doctor}', [doctorController::class, 'modificaDoctor'])->name('modificaDoctor');
//Route::POST('updateDoctor', [doctorController::class, 'updateDoctor'])->name('updateDoctor');

