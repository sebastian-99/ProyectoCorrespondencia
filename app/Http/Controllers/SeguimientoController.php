<?php

namespace App\Http\Controllers;
use DB;
use App\Models\seguimientosActividades;
use App\Models\archivosSeguimientos;
use App\Models\responsablesActividades;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class SeguimientoController extends Controller
{
    public function Seguimiento($idac)
    { 
        $idac = decrypt($idac);
        //dd($idac);
        $consulta = DB::table('actividades')
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
        )
        ->where('idac', $idac)
        ->get();    

        

        $tipo_actividad = DB::table('tipos_actividades')
        ->orderBy('nombre','Asc')
        ->get();
        $now=Carbon::now();

       // dd($consulta[0]);

            return view('SeguimientoActividades.Seguimiento')
            ->with('consulta', $consulta[0])
            ->with('tipo_actividad', $tipo_actividad)
            ->with('now', $now);            
    }

    public function AgregarSeguimiento(Request $request){
        
        $idseac = $request->idseac;
        $idreac_responsables_actividades = $request->idreac_responsables_actividades;
        $now=Carbon::now();
        $detalle = $request->detalle;
        $porcentaje = $request->porcentaje;
        $estado = $request->estado;
        $ruta = $request->ruta;

        if(\Storage::disk('local')->exists($request->archivosoculto)){
            
            $archivos = $request->archivosoculto;

        }elseif($request->file('archivos') != null){

            $file = $request->file('archivos');
            $archivos = $file->getClientOriginalName();
            $archivos = date('Ymd_His_') . $archivos;
            \Storage::disk('local')->put($archivos, \File::get($file));

        }else{
            
            $archivos = 'Sin archivo';
        }

        $seg_ac = new seguimientosActividades;
        $seg_ac->idreac_responsables_actividades = 4;
        $seg_ac->fecha = $now->format('d-m-y');
        $seg_ac->detalle = $request->detalle;
        $seg_ac->porcentaje = $request->porcentaje;
        $seg_ac->estado = $request->estado;

        $seg_ac->save();
        //return $seg_ac;

       // $ultimo = seguimientosActividades::max('idseac');

       $detalle = $request->detalle;


        $seg_arch = new archivosSeguimientos;
        $seg_arch->ruta =  $archivos;
        $seg_arch->idseac_seguimientos_actividades =  $seg_ac->idseac;
        $seg_arch->nombre = $archivos;
        $seg_arch->detalle = $request->detalle;


        $seg_arch->save();

        //dd($seg_ac);
        $consid = responsablesActividades::find($seg_ac->idreac_responsables_actividades);

        Session::flash('message', 'Se le ha dado un nuevo seguimiento a esta actividad');
        return redirect()->route('Seguimiento', ['idac' => encrypt($consid->idac_actividades)]);
    }
}
    