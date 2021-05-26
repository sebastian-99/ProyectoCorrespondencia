<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
#use App\Http\Controllers\AreasController;
#use App\Http\Controllers\UsersController;
use App\Http\Controllers\TiposActividadesController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 */

Route::resource('admin/tipos-actividades', TiposActividadesController::class, ['names' => 'tipos-actividades']);
#Route::resource('admin/areas', AreasController::class, ['names' => 'areas']);
#Route::resource('admin/users', UsersController::class, ['names' => 'users']);

