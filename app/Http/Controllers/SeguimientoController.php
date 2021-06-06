<?php

namespace App\Http\Controllers;

use DB;
use App\Models\actividades;
use App\Models\seguimientosActividades;
use App\Models\archivosSeguimientos;
use App\Models\responsablesActividades;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class SeguimientoController extends Controller
{

    public function actividades_asignadas()
    {
        $consult = DB::table('responsables_actividades')
            ->join('actividades', 'actividades.idac', '=', 'responsables_actividades.idac_actividades')
            ->join('users', 'users.idu', '=', 'responsables_actividades.idu_users')
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
                'actividades.status',
                'actividades.importancia',
                'users.idu',
                'users.titulo',
                'users.nombre',
                'users.app',
                'users.apm',

            ) 
            ->where('users.idu', '=', Auth()->user()->idu)
            ->get();
            

        return view('SeguimientoActividades.actividades_asignadas')
            ->with('consult', $consult);
    }

    public function Seguimiento($idac)
    {
        //Encriptar el id de la actividad que se esta consulutando 
        $idac = decrypt($idac);
        //Obtener detalles de la actividad
        $actividades = DB::table('actividades')
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
            ->where('idac', $idac)
            ->get();

        //Obtener datos del usuario
        $user = DB::table('users')
            ->join('tipos_usuarios', 'tipos_usuarios.idtu', '=', 'users.idtu_tipos_usuarios')
            ->join('areas', 'areas.idar', '=', 'users.idar_areas')
            ->select(
                'users.idu',
                'users.titulo',
                'users.nombre',
                'users.app',
                'users.apm',
                'tipos_usuarios.nombre as tipo_usuario',
                'areas.nombre as nombre_areas',
                'areas.idar',
                'areas.nombre as area',
            )
            ->where('users.idu', '=', Auth()->user()->idu)
            ->get();

        //Obtener la fecha actual 
        $now = Carbon::now();

        //Obtener el responsable que le esta dando seguimiento ala actividad
        $resp = DB::table('responsables_actividades')
            ->join('actividades', 'actividades.idac', '=', 'responsables_actividades.idac_actividades')
            ->select(
                'responsables_actividades.idreac',
                'responsables_actividades.idu_users',
                'responsables_actividades.idac_actividades',

            )
            ->where('responsables_actividades.idu_users', '=', Auth()->user()->idu)
            ->where('idac', $idac)
            ->get();

        //dd($resp);

        //Obtener los seguimientos que se le ha dado a la actividad asignada
        $seguimientos = DB::table('seguimientos_actividades')
            ->join('responsables_actividades', 'responsables_actividades.idreac', '=', 'seguimientos_actividades.idreac_responsables_actividades')
            ->select(
                'seguimientos_actividades.idseac',
                'seguimientos_actividades.fecha',
                'seguimientos_actividades.detalle',
                'seguimientos_actividades.estado',
                'seguimientos_actividades.porcentaje',
                'responsables_actividades.idu_users',
            )
            ->where('responsables_actividades.idu_users', '=', Auth()->user()->idu)

            ->get();

        return view('SeguimientoActividades.Seguimiento')
            ->with('actividades', $actividades[0])
            ->with('resp', $resp[0])
            ->with('seguimientos', $seguimientos)
            ->with('user', $user[0])
            ->with('now', $now);
    }

    public function AgregarSeguimiento(Request $request)
    {

        $idseac = $request->idseac;
        $idreac_responsables_actividades = $request->idreac_responsables_actividades;
        $now = Carbon::now();
        $detalle = $request->detalle;
        $porcentaje = $request->porcentaje;
        $estado = $request->estado;
        $ruta = $request->ruta;

        if (\Storage::disk('local')->exists($request->archivosoculto)) {

            $archivos = $request->archivosoculto;
        } elseif ($request->file('ruta') != null) {

            $file = $request->file('ruta');
            $archivos = $file->getClientOriginalName();
            $archivos = date('Ymd_His_') . $archivos;
            \Storage::disk('local')->put($archivos, \File::get($file));
        } else {

            $archivos = 'Sin archivo';
        }

        $seg_ac = new seguimientosActividades;
        $seg_ac->idreac_responsables_actividades = $idreac_responsables_actividades;
        $seg_ac->fecha = $now->format('y-m-d');
        $seg_ac->detalle = $request->detalle;
        $seg_ac->porcentaje = $request->porcentaje;
        $seg_ac->estado = $request->estado;

        $seg_ac->save();

        $detalle = $request->detalle;


        $seg_arch = new archivosSeguimientos;
        $seg_arch->ruta =  $archivos;
        $seg_arch->idseac_seguimientos_actividades =  $seg_ac->idseac;
        $seg_arch->nombre = $archivos;
        $seg_arch->detalle = $request->detalle;


        $seg_arch->save();

        $consid = responsablesActividades::find($seg_ac->idreac_responsables_actividades);

        Session::flash('message', 'Se le ha dado un nuevo seguimiento a esta actividad');
        return redirect()->route('Seguimiento', ['idac' => encrypt($consid->idac_actividades)]);
    }
    public function EliminarSeguimiento($idarse)
    {
        $seg = archivosSeguimientos::find($idarse);
        dd($seg);
        //$archs = archivosSeguimientos::find('idarseg');

        // archivosSeguimientos::find($archs)->delete();
        $seg->idreac_responsables_actividades = 4;

        $consid = responsablesActividades::find($seg->idreac_responsables_actividades);

        Session::flash('message2', 'Se ha eliminado este seguimiento');
        return redirect()->route('Seguimiento', ['idac' => encrypt($consid->idac_actividades)]);
    }
}
