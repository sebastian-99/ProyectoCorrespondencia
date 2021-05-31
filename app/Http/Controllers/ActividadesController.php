<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\actividades;
use App\Models\users;
use App\Models\seguimientos_actividades;
use DB;
use Arr;
class ActividadesController extends Controller
{
    public function reporte_actividades(){

        $consult = DB::SELECT("SELECT a.turno, a.fecha_creacion, a.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador, 
        CONCAT(a.fecha_inicio, ' al ', a.fecha_fin) AS periodo, a.importancia, ar.nombre, a.idac
        FROM actividades AS a
        INNER JOIN users AS us ON us.idu = a.idu_users
        INNER JOIN areas AS ar ON ar.idar = a.idar_areas");


        return view('Actividades.reporte')
        ->with('consult', $consult);
    }

    public function Detalles($idac){
        $query = DB::SELECT("SELECT res.idu_users, ar.nombre AS nombre_ar, us.nombre AS nombre_us, res.acuse, res.idreac
        FROM responsables_actividades AS res
        INNER JOIN users AS us ON us.idu = res.idu_users
        INNER JOIN areas AS ar ON ar.idar = us.idar_areas
        WHERE idac_actividades = $idac");
        return response()->json($query);
    }




    public function detallesSeguimiento($idac)
	{
        $consult = DB::SELECT("SELECT seg.idseac, seg.fecha, seg.detalle, seg.porcentaje, seg.estado, us.nombre, arch.ruta
        FROM seguimientos_actividades AS seg
        INNER JOIN responsables_actividades AS re ON re.idreac = seg.idreac_responsables_actividades
        INNER JOIN users AS us ON us.idu = re.idu_users
        INNER JOIN archivos_seguimientos AS arch ON arch.idseac_seguimientos_actividades = seg.idseac
            WHERE idreac_responsables_actividades = $idac");
        return view('SeguimientoActividades.detallesSeguimiento')
        ->with('consult', $consult);
	}



    public function actividades(){

        $hoy = Carbon::now()->locale('es_MX')->format('d-m-Y');
        $consul = DB::table('actividades')->count() + 1;
        $tipous = DB::table('areas')->get()->all();
        $tipo_actividad = DB::table('tipos_actividades')
        ->orderBy('nombre','Asc')
        ->get();
        return view('Actividades.actividades')
        ->with('hoy', $hoy)
        ->with('consul', $consul)
        ->with('tipo_actividad', $tipo_actividad)
        ->with('tipous', $tipous);
    }

    public function tipousuarios(Request $request){

        $id = $request->tipo_u;
        $id_seleccionado;

        for($b=0; $b < count($id); $b++){

            $consul = DB::Select("SELECT  u.idu, u.titulo,u.nombre,u.app,u.apm, tu.nombre AS tipo_area, a.nombre AS areas  FROM users AS u
            INNER JOIN tipos_usuarios AS tu ON tu.idtu = u.idtu_tipos_usuarios
            INNER JOIN areas AS a ON a.idar = u.idar_areas
            WHERE a.idar = $id[$b]");

            $id_seleccionado[$b] = $consul;

        }

        $id_sacado = Arr::flatten($consul);

        
    
        return response()->json($id_sacado);
    }

    public function insert_actividad(Request $r){
        
        $idusuario = $r->idusuario;
        $idar_areas = $r->idar_areas;
        $fechacreacion = $r->fechacreacion;
        $turno = $r->turno;
        $comunicado = $r->comunicado;
        $Asunto = $r->Asunto;
        $tipoactividad = $r->tipoactividad;
        $fechainicio = $r->fechainicio;
        $fechatermino = $r->fechatermino;
        $horadeinicio = $r->horadeinicio;
        $horatermino = $r->horatermino;
        $detalleactividad = $r->detalleactividad;
        
        if($r->file('archivos') != null){

            $file = $r->file('archivos');
            $archivos = $file->getClientOriginalName();
            $archivos = date('Ymd_His_') . $archivos;
            \Storage::disk('local')->put($archivos, \File::get($file));
        }else{
            $archivos = 'Sin archivo';
        }

        if($r->file('archivos2') != null){

            $file2 = $r->file('archivos2');
            $archivos2 = $file2->getClientOriginalName();
            $archivos2 = date('Ymd_His_') . $archivos2;
            \Storage::disk('local')->put($archivos2, \File::get($file2));
        }else{
            $archivos2 = 'Sin archivo';
        }
        
        if($r->file('archivos2') != null){

            $file3 = $r->file('archivos3');
            $archivos3 = $file3->getClientOriginalName();
            $archivos3 = date('Ymd_His_') . $archivos3;
            \Storage::disk('local')->put($archivos3, \File::get($file3));
        }else{
            $archivos3 = 'Sin archivo';
        }

        if($r->link != null){

            $link = $r->link;

        }else{
            $link = "Sin Link";
        }

        if($r->link2 != null){

            $link2 = $r->link2;

        }else{
            $link2 = "Sin Link";
        }

        if($r->link3 != null){

            $link3 = $r->link3;

        }else{
            $link3 = "Sin Link";
        }
        

        $tipousuario = $r->tipousuario;
        $tipousuarioarea = $r->tipousuarioarea;

        $estado = $r->estado;
        $importancia = $r->importancia;

        
        DB::Insert("INSERT INTO actividades (asunto, descripcion, fecha_creacion, turno, comunicado, fecha_inicio,
                    hora_inicio, fecha_fin, hora_fin, idtac_tipos_actividades, idar_areas, idu_users, status,
                    importancia, archivo1, archivo2, archivo3, link1, link2, link3)
                    VALUES ('$Asunto', '$detalleactividad', '$fechacreacion', '$turno', '$comunicado', '$fechainicio',
                    '$horadeinicio', '$fechainicio', '$horatermino', '$tipoactividad', '$idar_areas', '$idusuario', '$estado',
                    '$importancia', '$archivos', '$archivos2', '$archivos3', '$link', '$link2', '$link3')");

        $consul = DB::table('actividades')->max('idac');

        for($i=0; $i < count($tipousuarioarea); $i++){

            DB::INSERT("INSERT INTO participantes (idac ,id_users) VALUES ($consul,'$tipousuarioarea[$i]')");
        }


        
    }
    
    public function actividades_modificacion($id){
        
    
        $consul = DB::table('actividades')->where('idac', $id)
        ->join('users', 'users.idu', '=', 'actividades.idu_users')
        ->join('areas', 'areas.idar', '=', 'actividades.idar_areas')
        ->select(
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

        

        /* $tipous = DB::table('areas')->get()->all();*/

        $tipous = DB::SELECT("SELECT a.nombre, a.`idar`
        FROM actividades AS ac 
        INNER JOIN participantes AS p ON p.idac = ac.idac
        INNER JOIN users AS u ON u.idu = p.id_users
        INNER JOIN areas AS a ON a.idar = u.idtu_tipos_usuarios
        WHERE ac.idac = $id");


        $users = DB::SELECT("SELECT u.idu, CONCAT(u.titulo, ' ' , u.app, ' ', u.apm, ' ' , u.nombre) AS usuario
        FROM actividades AS ac 
        INNER JOIN participantes AS p ON p.idac = ac.idac
        INNER JOIN users AS u ON u.idu = p.id_users
        INNER JOIN areas AS a ON a.idar = u.idtu_tipos_usuarios
        WHERE ac.idac = $id");

        

        $tipo_actividad = DB::table('tipos_actividades')
        ->orderBy('nombre','Asc')
        ->get();


        return view('Actividades.modificar_actividad')
        ->with('consul', $consul)
        ->with('tipo_actividad', $tipo_actividad)
        ->with('tipous', $tipous)
        ->with('users', $users);
    }

    

}
