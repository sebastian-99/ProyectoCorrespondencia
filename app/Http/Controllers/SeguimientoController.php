<?php

namespace App\Http\Controllers;

use DB;
use App\Models\actividades;
use App\Models\seguimientosActividades;
use App\Models\archivosSeguimientos;
use App\Models\responsablesActividades;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Session;

class SeguimientoController extends Controller
{

    public function actividades_asignadas()
    {
        $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador, 
        ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area, ra.acuse, ra.idu_users, 
        porcentaje(ac.idac) AS porcentaje
        FROM actividades AS ac
        INNER JOIN users AS us ON us.idu = ac.idu_users
        INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
        LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
        LEFT JOIN seguimientos_actividades AS sa ON sa.idreac_responsables_actividades = idreac
        GROUP BY ac.idac
        ORDER BY ac.fecha_creacion DESC");

        $array = array();

        function recorrer($value)
        {
            $arr = (gettype($value) == "string") ? explode('-', $value) : null;
            return $arr;
        }

        function AB($data)
        {
            if (gettype($data) == "array") {

                return $data[0] . " de " . $data[1];
            } else {
                return 0;
            }
        }

        function C($data)
        {
            if (gettype($data) == "array") {

                return number_format($data[2], 2, '.', ' ') . '%';
            } else {
                return 0.00;
            }
        }

        function ver($idac)
        {
            return "<a class='btn btn-success mt-1 btn-sm' href=" . route('Seguimiento', ['idac' => encrypt($idac)]) . "><i class='nav-icon fas fa-eye'></i></a>";
        }

        foreach ($consult as $c) {

            $data = recorrer($c->porcentaje);

            array_push($array, array(

                'turno' => $c->turno,
                'fecha_creacion' => Carbon::parse($c->fecha_creacion)->locale('es')->isoFormat('D MMMM h:mm a'),
                'asunto' => $c->asunto,
                'creador' => $c->creador,
                'periodo' => Carbon::parse($c->fecha_inicio)->locale('es')->isoFormat('D MMMM') . ' - ' . Carbon::parse($c->fecha_fin)->locale('es')->isoFormat('D MMMM'),
                'importancia' => $c->importancia,
                'area' => $c->area,
                'recibo' => AB($data),
                'porcentaje' =>  C($data),
                'operaciones' => ver($c->idac),
            ));
        }

        $json = json_encode($array);

        return view('SeguimientoActividades.actividades_asignadas')
            ->with('json', $json);
    }

    public function Seguimiento($idac)
    {
        //Encriptar el id de la actividad que se esta consulutando 
        $idac = decrypt($idac);

        //Obtener detalles de la actividad

        $actividades = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto, ac.descripcion,
        CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador, ac.comunicado,
        ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as nombre_area, 
        ac.archivo1, ac.archivo2, ac.archivo3, ac.link1, ac.link2, ac.link3, 
        ac.status, porcentaje(ac.idac) AS porcentaje
        FROM actividades AS ac
        INNER JOIN users AS us ON us.idu = ac.idu_users
        INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
        WHERE ac.idac = $idac");


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
                'responsables_actividades.acuse',

            )
            ->where('responsables_actividades.idu_users', '=', Auth()->user()->idu)
            ->where('idac', $idac)
            ->get();

        //Marca que el usuario le ha empezado a dar seguimiento a su actividad en el campo acuse = 1

        $consulta = $resp[0]->idreac;
        $acuse = $resp[0]->acuse;
        if ($acuse == '0') {
            DB::UPDATE("UPDATE responsables_actividades SET acuse = '1' WHERE idreac = $consulta");
        }




        //Obtener los seguimientos que se le ha dado a la actividad asignada
        $seguimientos = DB::table('seguimientos_actividades')
            ->join('responsables_actividades', 'responsables_actividades.idreac', '=', 'seguimientos_actividades.idreac_responsables_actividades')
            ->leftJoin('archivos_seguimientos as arse', 'arse.idseac_seguimientos_actividades', '=', 'seguimientos_actividades.idseac')
            ->select(
                'seguimientos_actividades.idseac',
                'seguimientos_actividades.fecha',
                'seguimientos_actividades.detalle',
                'seguimientos_actividades.estado',
                'seguimientos_actividades.porcentaje',
                'responsables_actividades.idu_users',
                'responsables_actividades.idac_actividades',
                'seguimientos_actividades.idreac_responsables_actividades',
                'arse.idarseg',
            )
            ->where('responsables_actividades.idac_actividades', '=', $idac)
            ->where('responsables_actividades.idu_users', '=', Auth()->user()->idu)
            ->groupBy('seguimientos_actividades.idseac')
            ->get();
        //return $seguimientos;

        $array_sa = array();


        function detalles($idseac, $idarseg)
        {
            return "<a href='javascript:void(0)' data-toggle='tooltip' data-id=" . encrypt($idseac) . "  data-original-title='DetallesArchivos' class='edit btn btn-success btn-sm DetallesArchivos'><i class='nav-icon fas fa-eye'></i></a>
            <a class='btn btn-danger mt-1 btn-sm' href=" . route('EliminarSeguimiento', ['idarse' => encrypt($idarseg), 'idseac' => encrypt($idseac)]) . "><i class='nav-icon fas fa-trash'></i></a>";
        }

        foreach ($seguimientos as $seg_ac) {

            array_push($array_sa, array(

                'idseac' => $seg_ac->idseac,
                'fecha' => Carbon::parse($seg_ac->fecha)->locale('es')->isoFormat('D MMMM h:mm a'),
                'detalle' => $seg_ac->detalle,
                'estado' => $seg_ac->estado,
                'porcentaje' => $seg_ac->porcentaje,
                'evidencia' => detalles($seg_ac->idseac, $seg_ac->idarseg),
            ));
        }

        $json_sa = json_encode($array_sa);


        return view('SeguimientoActividades.Seguimiento')
            ->with('actividades', $actividades[0])
            ->with('resp', $resp[0])
            ->with('json_sa', $json_sa)
            ->with('user', $user[0])
            ->with('now', $now);
    }

    public function AgregarSeguimiento(Request $request)
    {
        $idseac = $request->idseac;
        $idreac_responsables_actividades = $request->idreac_responsables_actividades;
        $idseac_seguimientos_actividades = $request->idseac_seguimientos_actividades;
        $now = Carbon::now();
        $detalle = $request->detalle;
        $porcentaje = $request->porcentaje;
        $estado = $request->estado;
        $ruta = $request->ruta;

        $seg_ac = new seguimientosActividades;
        $seg_ac->idreac_responsables_actividades = $idreac_responsables_actividades;
        $seg_ac->fecha = $now->format('y-m-d');
        $seg_ac->detalle = $request->detalle;
        $seg_ac->porcentaje = $request->porcentaje;
        $seg_ac->estado = $request->estado;

        $seg_ac->save();

        //Insertar archivos en tabla archivos_seguimientos

        $max_size = (int) ini_get('upload_max_filesize') * 10240;
        $user_id = Auth()->user()->idu;
        $files = $request->file('ruta');

        if ($request->hasFile('ruta')) {

            foreach ($files as $file) {
                if (Storage::putFileAs('/Seguimientos/', $file, $file->getClientOriginalName())) {

                    archivosSeguimientos::create([
                        'idseac_seguimientos_actividades' => $idseac_seguimientos_actividades = $seg_ac->idseac,
                        'nombre' => $file->getClientOriginalName(),
                        'ruta' => $file->getClientOriginalName(),
                        'detalle' => $detalle,
                    ]);
                }
            }
        } else {
            archivosSeguimientos::create([
                'idseac_seguimientos_actividades' => $idseac_seguimientos_actividades = $seg_ac->idseac,
                'nombre' => 'Sin archivo',
                'ruta' => 'Sin archivo',
                'detalle' => $detalle,
            ]);
        }



        $consid = responsablesActividades::find($seg_ac->idreac_responsables_actividades);

        Session::flash('message', 'Se le ha dado un nuevo seguimiento a esta actividad');
        return redirect()->route('Seguimiento', ['idac' => encrypt($consid->idac_actividades)]);
    }
    public function EliminarSeguimiento($idarseg, $idseac)

    {
        //$ultimo = archivosSeguimientos::find('idarse')->orderBy('idarse')->desc();
        $idarseg = decrypt($idarseg);
        $idseac = decrypt($idseac);

        $elim = DB::DELETE("DELETE FROM archivos_seguimientos  
        where idseac_seguimientos_actividades =$idseac
        ");

        $elim_s = DB::DELETE("DELETE FROM seguimientos_actividades  
        where idseac =$idseac
        ");

        return "Seguimiento eliminado";
    }
}
