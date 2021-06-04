<?php

use App\Http\Controllers\Sistema\Actividades\ActividadesController;
use Illuminate\Support\Facades\Route;

Route::get('/actividades/dashboard',[ActividadesController::class,'dashboard'])->name('actividades.dashboard');
