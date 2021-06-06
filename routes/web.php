<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TiposActividadesController;

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
    return view('welcome');
});
*/


Route::resource('tipos-actividades', TiposActividadesController::class, ['names' => 'tipos-actividades']);
Route::resource('users', UsersController::class, ['names' => 'users']);
Route::resource('areas', AreasController::class, ['names' => 'areas']);

