<?php

namespace App\Http\Controllers;
use DB;
use App\Models\seguimientosActividades;
use App\Models\actividades;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    public function Seguimiento($idac)
    {
        

        $consulta = DB::table('actividades')->where('idac', $idac)
        ->join('users', 'users.idu', '=', 'actividades.idu_users')
        ->join('areas', 'areas.idar', '=', 'actividades.idar_areas')
        ->select(
            'actividades.idac',
            'actividades.asunto',
            'actividades.descripcion',
            'actividades.fecha_creacion',
            'actividades.turno',
            'actividades.comunicado',
            'actividades.fecha_inicio',
            'actividades.fecha_fin',
            'actividades.hora_inicio',
            'actividades.hora_fin',
            'areas.nombre as nombre_area',
            'users.titulo',
            'users.nombre',
            'users.app',
            'users.apm',
            'actividades.status',
            'actividades.importancia',
            'actividades.archivo1',
            'actividades.archivo2',
            'actividades.archivo3',
            'actividades.link1',
            'actividades.link2',
            'actividades.link3',
        )
        ->get();


            return view('SeguimientoActividades/Seguimiento', compact('consulta'));
           
            
    }
}
