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

    public function actividades_asignadas(){

        $consult = DB::SELECT("SELECT a.idac ,a.turno, a.fecha_creacion, a.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador, 
        CONCAT(a.fecha_inicio, ' al ', a.fecha_fin) AS periodo, a.importancia, ar.nombre, a.activo
        FROM actividades AS a
        INNER JOIN users AS us ON us.idu = a.idu_users
        INNER JOIN areas AS ar ON ar.idar = a.idar_areas");
    

        return view('SeguimientoActividades.actividades_asignadas')
        ->with('consult', $consult);
    }

    public function Seguimiento($idac)
    { 
        //Encriptar el id de la actividad que se esta consulutando 
        $idac = decrypt($idac);
        //Obtener detalles de la actividad
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
            'actividades.archivo1',
            'actividades.archivo2',
            'actividades.archivo3',
            'actividades.link1',
            'actividades.link2',
            'actividades.link3',
        )
        ->where('idac', $idac)
        ->get();       
        //Obtener el tipo de actividad
        $tipo_actividad = DB::table('tipos_actividades')
        ->orderBy('nombre','Asc')
        ->get();
        //Obtener la fecha actual 
        $now=Carbon::now();

        //Obtener los seguimientos que se le ha dado a la actividad asignada
        $seguimientos = seguimientosActividades::all();

            return view('SeguimientoActividades.Seguimiento')
            ->with('consulta', $consulta[0])
            ->with('tipo_actividad', $tipo_actividad)
            ->with('seguimientos', $seguimientos)            
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

        }elseif($request->file('ruta') != null){

            $file = $request->file('ruta');
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
    public function Descarga($idac){
        $consulta = actividades::where('idac', $idac)->firstOrFail();
        $pathToFile = ("public/archivos/".$consulta->archivo1);
        return response()->download($pathToFile);
    }
}
    