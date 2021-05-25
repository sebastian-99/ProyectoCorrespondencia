<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;
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
    return view('layout/layout');
});
Route::get('/reporte', [Reportecontroller::class, 'reporte']);

Route::get('Detalles/{id}', [Reportecontroller::class, 'Detalles'])->name('Detalles');

