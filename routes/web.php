<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CuentasController;
use App\Http\Controllers\EncryptController;

use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\SeguimientoController;

use App\Http\Controllers\TiposActividadesController;
use App\Http\Controllers\Sistema\Panel\PanelController;
use App\Http\Controllers\Sistema\TipoAreas\TipoAreasController;
use App\Http\Controllers\Graficas\GraficasPorTipoAreaController;
use App\Http\Controllers\Graficas\GraficasDeActividadesCreadasController;

use App\Http\Controllers\CorreoController;

Route::get('pruebaCorreo', [CorreoController::class,'enviarNuevo'])->name('pruebaCorreo');

// borrar caché de la aplicación
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 'Application cache cleared';
});

// borrar caché de configuración
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return 'Config cache cleared';
}); 


Route::redirect('/', 'panel');
Auth::routes();

Route::middleware(['auth','activo'])->group(function () {

    Route::get('/panel', [PanelController::class,'panel'])->name('panel');
    Route::get('/panel/get-actividades-hoy/{idu}', [PanelController::class,'getActividadesHoy']);
    Route::get('/panel/get-actividades-pendientes/{idu}', [PanelController::class,'getActividadesPendientes']);
    Route::get('/panel/get-actividades-por-mes/{idu}', [PanelController::class,'getActividadesPorMes']);
    Route::get('/panel/get-actividades-cerradas/{idu}', [PanelController::class,'getActividadesCerradas']);
    Route::get('/panel/get-actividades-en-seguimiento/{idu}', [PanelController::class,'getActividadesEnSeguimiento']);
    Route::get('/panel/get-actividades-completadas', [PanelController::class,'getActividadesCompletadas']);
    Route::get('/panel/get-actividades-en-proceso', [PanelController::class,'getActividadesEnProceso']);
    Route::get('/panel/get-actividades-sin-entregar', [PanelController::class,'getActividadesSinEntregar']);
    Route::get('/panel/get-actividades-con-acuse-de-recibido', [PanelController::class,'getActividadesConAcuseDeRecibido']);
    Route::get('/panel/get-actividades-sin-acuse-de-recibido', [PanelController::class,'getActividadesSinAcuseDeRecibido']);


//////////////////////////////////////////////  U S U A R I O S  /////////////////////////////////////////////////////////////////
    Route::resource('admin/users', UsersController::class, ['names' => 'users']);
    Route::get('editar-perfil', [CuentasController::class, 'editar_perfil'])->name('editar-perfil');
    Route::post('editar-perfil', [CuentasController::class, 'editar_perfil_post'])->name('editar-perfil.post');
//-------------------------------------------------------------------------------------------------------------------------------

//////////////////////////////////////// T I P O S  A C T I V I D A D E S  ///////////////////////////////////////////////////////
    Route::resource('admin/tipos-actividades', TiposActividadesController::class, ['names' => 'tipos-actividades']);
//-------------------------------------------------------------------------------------------------------------------------------

////////////////////////////////////////////////  Á R E A S  /////////////////////////////////////////////////////////////////////
    Route::resource('admin/areas', AreasController::class, ['names' => 'areas']);
//-------------------------------------------------------------------------------------------------------------------------------

//////////////////////////////////////////  S E G U I M I E N T O S  /////////////////////////////////////////////////////////////
    Route::get('actividades_asignadas', [SeguimientoController::class,'actividades_asignadas'])->name('actividades_asignadas');
    Route::get('fecha_actividades_asignadas', [SeguimientoController::class,'fecha_actividades_asignadas'])->name('fecha_actividades_asignadas');
    Route::get('DetallesAsignacion/{idac}', [SeguimientoController::class, 'DetallesAsignacion'])->name('DetallesAsignacion');
    Route::get('Seguimiento/{idac}', [SeguimientoController::class, 'Seguimiento'])->name('Seguimiento');
    Route::POST('AgregarSeguimiento', [SeguimientoController::class,'AgregarSeguimiento'])->name('AgregarSeguimiento');
    Route::get('EliminarSeguimiento/{idarse}/{idseac}/{idac}', [SeguimientoController::class, 'EliminarSeguimiento'])->name('EliminarSeguimiento');
    Route::get('DetallesArchivos/{idarc}', [SeguimientoController::class, 'DetallesArchivos'])->name('DetallesArchivos');
    Route::post('aceptarActividad', [SeguimientoController::class,'aceptarActividad'])->name('aceptarActividad');
    Route::post('rechazarActividad', [SeguimientoController::class,'rechazarActividad'])->name('rechazarActividad');
//-------------------------------------------------------------------------------------------------------------------------------

////////////////////////////////////////////  A C T I V I D A D E S  /////////////////////////////////////////////////////////////
    Route::get('reporte_actividades', [ActividadesController::class,'reporte_actividades'])->name('reporte_actividades');
    Route::get('fecha_ajax', [ActividadesController::class,'fecha_ajax'])->name('fecha_ajax');
    Route::get('activacion/{id}/{activo}', [ActividadesController::class,'activacion'])->name('activacion');
    Route::get('aprobacion/{id}/{aprobacion}', [ActividadesController::class,'aprobacion'])->name('aprobacion');
    Route::get('actividades', [ActividadesController::class,'actividades'])->name('create_actividades');
    Route::get('tipousuarios', [ActividadesController::class,'tipousuarios'])->name('ajax_tipousuarios');
    Route::get('quitar_ajax', [ActividadesController::class,'quitar_ajax'])->name('quitar_ajax');
    Route::get('quitar_ajax2', [ActividadesController::class,'quitar_ajax2'])->name('quitar_ajax2');
    Route::POST('insert_actividad', [ActividadesController::class,'insert_actividad'])->name('insert_actividad');
    Route::get('actividades_modificacion/{id}', [ActividadesController::class,'actividades_modificacion'])->name('edit_modificacion');
    Route::POST('update_actividades', [ActividadesController::class,'update_actividades'])->name('update_actividades');
    Route::post('updateRechazo', [ActividadesController::class, 'updateRechazo'])->name('updateRechazo');
    Route::get('EliminarResponsables/{idreac}', [ActividadesController::class, 'EliminarResponsables'])->name('EliminarResponsables');
    Route::get('Detalles/{id}', [ActividadesController::class, 'Detalles'])->name('Detalles');
    Route::get('detallesSeguimiento/{idac}', [ActividadesController::class, 'detallesSeguimiento'])->name('detallesSeguimiento');
    Route::get('DetallesArchivos/{idarseg}', [ActividadesController::class, 'DetallesArchivos'])->name('DetallesArchivos');
    Route::get('actividades_creadas/{id}', [ActividadesController::class, 'actividades_creadas'])->name('actividades_creadas');
    Route::get('actividades_pendientes/{id}', [ActividadesController::class, 'actividades_pendientes'])->name('actividades_pendientes');
    Route::get('ajax_filtro_fecha', [ActividadesController::class, 'ajax_filtro_fecha'])->name('ajax_filtro_fecha');
    /* Generación PDF */
    Route::get('pdf/{idac}', [ActividadesController::class, 'pdf'])->name('pdf');
    /* Generación Firma Digital */
    Route::get('hello', [EncryptController::class,'index']);
//-------------------------------------------------------------------------------------------------------------------------------



    Route::get('/dashboard/{user}',[GraficasPorTipoAreaController::class,'dashboard']);
    Route::post('/dashboard/{user}',[GraficasPorTipoAreaController::class,'getEstadisticasDeActividades']);

    Route::post('/dashboard/{user}/get-actividades-completadas', [GraficasPorTipoAreaController::class,'actividadesCompletadas']);
    Route::post('/dashboard/{user}/get-actividades-en-proceso', [GraficasPorTipoAreaController::class,'actividadesEnProceso']);
    Route::post('/dashboard/{user}/get-actividades-sin-entregar', [GraficasPorTipoAreaController::class,'actividadesSinEntregar']);
    Route::post('/dashboard/{user}/get-actividades-con-acuse-de-recibido', [GraficasPorTipoAreaController::class,'actividadesConAcuseDeRecibido']);
    Route::post('/dashboard/{user}/get-actividades-sin-acuse-de-recibido', [GraficasPorTipoAreaController::class,'actividadesSinAcuseDeRecibido']);
    Route::post('/dashboard/{user}/get-actividades-por-area', [GraficasPorTipoAreaController::class,'getActividadesPorTipoArea']);

    Route::post('/dashboard/{user}/actividades-completadas-en-tiempo', [GraficasPorTipoAreaController::class,'actividadesCompletadasEnTiempo'])->name('actividades.en-tiempo');
    Route::post('/dashboard/{user}/actividades-completadas-fuera-de-tiempo', [GraficasPorTipoAreaController::class,'actividadesCompletadasFueraDeTiempo']);
    Route::post('/dashboard/{user}/actividades-en-proceso-en-tiempo', [GraficasPorTipoAreaController::class,'actividadesEnProcesoEnTiempo']);
    Route::post('/dashboard/{user}/actividades-en-proceso-fuera-de-tiempo', [GraficasPorTipoAreaController::class,'actividadesEnProcesoFueraDeTiempo']);

    Route::get('/seguimiento/{idac}', [GraficasPorTipoAreaController::class,'seguimiento']);

    Route::get('/dashboard/{user}/actividades-creadas',[GraficasDeActividadesCreadasController::class,'dashboard']);
    Route::post('/dashboard/{user}/actividades-creadas',[GraficasDeActividadesCreadasController::class,'getEstadisticasDeActividades']);

    Route::post('/dashboard/{user}/actividades-creadas/get-actividades-completadas', [GraficasDeActividadesCreadasController::class,'actividadesCompletadas']);
    Route::post('/dashboard/{user}/actividades-creadas/get-actividades-en-proceso', [GraficasDeActividadesCreadasController::class,'actividadesEnProceso']);
    Route::post('/dashboard/{user}/actividades-creadas/get-actividades-sin-entregar', [GraficasDeActividadesCreadasController::class,'actividadesSinEntregar']);
    Route::post('/dashboard/{user}/actividades-creadas/get-actividades-con-acuse-de-recibido', [GraficasDeActividadesCreadasController::class,'actividadesConAcuseDeRecibido']);
    Route::post('/dashboard/{user}/actividades-creadas/get-actividades-sin-acuse-de-recibido', [GraficasDeActividadesCreadasController::class,'actividadesSinAcuseDeRecibido']);
    Route::post('/dashboard/{user}/actividades-creadas/get-actividades-por-area', [GraficasDeActividadesCreadasController::class,'getActividadesPorTipoArea']);

    Route::post('/dashboard/{user}/actividades-creadas/actividades-completadas-en-tiempo', [GraficasDeActividadesCreadasController::class,'actividadesCompletadasEnTiempo'])->name('actividades.en-tiempo');
    Route::post('/dashboard/{user}/actividades-creadas/actividades-completadas-fuera-de-tiempo', [GraficasDeActividadesCreadasController::class,'actividadesCompletadasFueraDeTiempo']);
    Route::post('/dashboard/{user}/actividades-creadas/actividades-en-proceso-en-tiempo', [GraficasDeActividadesCreadasController::class,'actividadesEnProcesoEnTiempo']);
    Route::post('/dashboard/{user}/actividades-creadas/actividades-en-proceso-fuera-de-tiempo', [GraficasDeActividadesCreadasController::class,'actividadesEnProcesoFueraDeTiempo']);
    Route::get('/detalle-actividad/{idac}', [GraficasDeActividadesCreadasController::class,'detalleActividad']);

});

Route::get('php', function (){
    phpinfo();
});
