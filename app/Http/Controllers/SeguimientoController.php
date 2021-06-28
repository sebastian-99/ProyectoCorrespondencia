<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Actividades as actividades;
use App\Models\SeguimientosActividades as seguimientosActividades;
use App\Models\ArchivosSeguimientos as archivosSeguimientos;
use App\Models\ResponsablesActividades as responsablesActividades;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Session;
use Brs\FunctionPkg;

class SeguimientoController extends Controller
{

    public function actividades_asignadas()
    {
        //Filtrar solo mis actividades que tengo asignadas
        $id_user = Auth()->user()->idu;

        $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
        ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, 
        porcentaje(ac.idac, $id_user) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
        FROM actividades AS ac
        INNER JOIN users AS us ON us.idu = ac.idu_users
        INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
        INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
        LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
        WHERE ra.idu_users = $id_user
        GROUP BY ac.idac
        ORDER BY ac.fecha_creacion DESC");

        $array = array();


        function recorrer($value)
        {
            if (gettype($value) == "string") {
                $val = explode('*', $value);
                $arr = array('1' => explode('-', $val[0]), '2' => $val[1]);
            } else {
                $arr = null;
            }
            return $arr;
        }

        function AB($data)
        {
            if (gettype($data) == "array") {

                return $data['1'][0] . " de " . $data['1'][1];
            } else {
                return 0;
            }
        }

        function C($data)
        {
            if (gettype($data) == "array") {
                return number_format($data['2'], 0, '.', ' ') . '%';
            } else {
                return 0;
            }
        }

        function D($status, $end_date, $data, $acuse)
        {
            if (gettype($data) == "array") {
                $data = number_format($data['2'], 0, '.', ' ');
            } else {
                $data = 0;
            }
            $date = Carbon::now()->locale('es')->isoFormat("Y-MM-D");

            //return ($data > $end_date ? "es mayor" : "No es mayor");

            if ($date <= $end_date && $data < 100 && $acuse == 1) {

                return "En proceso – En Tiempo";
            } elseif ($date <= $end_date  && $data == 100 && $acuse == 1) {

                return "Concluido – En tiempo";
            } elseif ($date >= $end_date  && $data < 100 && $acuse == 1) {

                return "En proceso - Fuera de Tiempo";
            } elseif ($date >= $end_date  && $data == 100 && $acuse == 1) {

                return "Concluido – Fuera de Tiempo";
            } elseif ($acuse == 2) {

                return "Acuse rechazado";
            } elseif ($status == 3) {

                return "Cancelado";
            } else {
                return "Sin aceptar acuse";
            }
        }

        function ver($idac)
        {
            //consulta para ver si el acuse se recibio
            $id_user = Auth()->user()->idu;

            $ver_acuse = DB::SELECT("SELECT ra.acuse, ra.idreac
            FROM actividades AS ac
            LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
            WHERE ra.idu_users = $id_user
            AND ra.idac_actividades = $idac
            ");
            if ($ver_acuse[0]->acuse == 2) {
                return "<a class='btn btn-sm btn-danger' disabled><i class='nav-icon fas fa-ban'></i></a>";
            }

            if ($ver_acuse[0]->acuse == 1) {
                return "<a class='btn btn-success mt-1 btn-sm' id='btn-mostrar' href=" . route('Seguimiento', ['idac' => encrypt($idac)]) . "><i class='nav-icon fas fa-eye'></i></a>";
            } else {
                $idreac = $ver_acuse[0]->idreac;
                return "<a class='btn btn-success mt-1 btn-sm' id='$idreac' href=" . route('Seguimiento', ['idac' => encrypt($idac)]) . " hidden><i class='nav-icon fas fa-eye'></i></a>
                    <a href='javascript:void(0)' data-toggle='tooltip' data-id=" . encrypt($idac) . "  data-original-title='DetallesAsignacion' class='edit btn btn-primary btn-sm DetallesAsignacion' id='detalle'><i class='nav-icon fas fa-user-check'></i></a>
                    <a class='btn btn-sm btn-danger' id='mensaje' hidden disabled><i class='nav-icon fas fa-ban'></i></a>";
            }
        }

        foreach ($consult as $c) {

            $data = recorrer($c->porcentaje);

            array_push($array, array(

                'turno' => $c->turno,
                'fecha_creacion' => Carbon::parse($c->fecha_creacion)->locale('es')->isoFormat('D [de] MMMM [del] YYYY'),
                'asunto' => $c->asunto,
                'tipo_actividad' => $c->tipo_actividad,
                'descripcion' => $c->descripcion,
                'creador' => $c->creador,
                'periodo' => Carbon::parse($c->fecha_inicio)->locale('es')->isoFormat('D MMMM') . ' al ' . Carbon::parse($c->fecha_fin)->locale('es')->isoFormat('D MMMM [del] YYYY'),
                'importancia' => $c->importancia,
                'area' => $c->area,
                'recibo' => AB($data),
                'porcentaje' => C($data),
                'estado' =>  D($c->status, $c->fecha_fin, $data, $c->acuse),
                'operaciones' => ver($c->idac),
            ));
        }

        $json = json_encode($array);

        return view('SeguimientoActividades.actividades_asignadas')
            ->with('json', $json);
    }

    public function aceptarActividad(Request $request)
    {
        $contraseña = $request->pass;
        $idac = decrypt($request->id);

        if (password_verify($contraseña, Auth()->User()->password)) {
            $new = new FunctionPkg;
            $id_user = Auth()->user()->idu;
            $firma = $new->Encrypt($idac, $id_user, Auth()->User()->nombre);

            $cons = DB::UPDATE("UPDATE responsables_actividades SET
                acuse = 1, fecha_acuse = CURDATE(), firma = '$firma'
                WHERE idu_users = $id_user AND idac_actividades = $idac");
            Session::flash('message', 'Ahora podrás darle seguimiento a esta actividad');
            //return response()->json('aceptado');
        } else {
            Session::flash('message2', 'Contraseña incorrecta, favor de verificar que la contraseña este escrita correctamente');
            //return 'Contraseña incorrecta';
        }
    }

    public function rechazarActividad(Request $request)
    {
        $idac = decrypt($request->id_a);
        $razon_r = $request->rechazo;
        $id_user = Auth()->user()->idu;

        $rechazar = DB::UPDATE("UPDATE responsables_actividades SET
                acuse = 2, fecha_acuse = CURDATE(), razon_rechazo = '$razon_r'
                WHERE idu_users = $id_user AND idac_actividades = $idac");
        Session::flash('rechazo', 'Usted ha rechazado la actividad, por lo que no se ha bloqueado la actividad, para desbloquear la actividad deberá ponerse en contacto con el creador de la actividad');
        //return response()->json('aceptado');


        return response()->json('aceptado');
    }

    public function DetallesAsignacion($idac)
    {
        $idac = decrypt($idac);
        $id_user = Auth()->user()->idu;

        $actividad = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto, ac.descripcion,
        CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador, ac.comunicado,
        ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as nombre_area,
        ac.status, porcentaje(ac.idac,$id_user) AS porcentaje
        FROM actividades AS ac
        INNER JOIN users AS us ON us.idu = ac.idu_users
        INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
        WHERE ac.idac = $idac");

        return response()->json($actividad);
    }

    public function Seguimiento($idac)
    {
        //Encriptar el id de la actividad que se esta consulutando
        $idac = decrypt($idac);
        $id_user = Auth()->user()->idu;



        //Obtener detalles de la actividad

        $actividades = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto, ac.descripcion,
        CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador, ac.comunicado,
        ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as nombre_area,
        ac.archivo1, ac.archivo2, ac.archivo3, ac.link1, ac.link2, ac.link3, ta.nombre as tipo_act,
        ac.status, porcentaje(ac.idac,$id_user) AS porcentaje
        FROM actividades AS ac
        INNER JOIN users AS us ON us.idu = ac.idu_users
        INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
        INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
        WHERE ac.idac = $idac");

        $archivo1=null;
        $archivo2=null;
        $archivo3=null;

        if($actividades[0]->archivo1 != 'Sin archivo'){
            $archivo1=explode("_", $actividades[0]->archivo1)[2];
        }
        if($actividades[0]->archivo2 != 'Sin archivo'){
            $archivo2=explode("_", $actividades[0]->archivo2)[2];
        }
        if($actividades[0]->archivo3 != 'Sin archivo'){
            $archivo3=explode("_", $actividades[0]->archivo3)[2];
        }

        
        $general = explode('*', $actividades[0]->porcentaje)[2];
        $general = number_format($general, 0);
        $general1 = explode('*', $actividades[0]->porcentaje)[1];

        $end_date = $actividades[0]->fecha_fin;



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

        //Porcenteje mas alto del de avance
        $max_ai = DB::SELECT("SELECT MAX(seg.porcentaje) AS avance_i
        FROM responsables_actividades AS res
        JOIN seguimientos_actividades AS seg ON seg.idreac_responsables_actividades = res.idreac
        WHERE idac_actividades = $idac AND idu_users = $id_user
        ");

        //ver el estado de la actividad
        $date = Carbon::now()->locale('es')->isoFormat("Y-MM-D");

        //return ($data > $end_date ? "es mayor" : "No es mayor");
        if ($date <= $end_date && $max_ai[0]->avance_i < 100 && $resp[0]->acuse == 0) {

            $est_act = "En proceso – En Tiempo";
        } elseif ($date <= $end_date && $max_ai[0]->avance_i == 100 && $resp[0]->acuse == 1) {

            $est_act = "Concluido – En tiempo";
        } elseif ($date >= $end_date && $max_ai[0]->avance_i < 100 && $resp[0]->acuse == 0) {

            $est_act = "En proceso – Fuera de tiempo";
        } elseif ($date <= $end_date && $max_ai[0]->avance_i < 100 && $resp[0]->acuse == 1) {

            $est_act = "En proceso – En Tiempo";
        } elseif ($date >= $end_date && $max_ai[0]->avance_i < 100 && $resp[0]->acuse == 1) {

            $est_act = "En proceso - Fuera de Tiempo";
        } elseif ($date >= $end_date && $max_ai[0]->avance_i == 100 && $resp[0]->acuse == 1) {

            $est_act = "Concluido – Fuera de Tiempo";
        } elseif ($resp[0]->acuse == 2 && $resp[0]->acuse == 2) {

            $est_act = "Acuse rechazado";
        } elseif ($actividades[0]->status == 3) {

            $est_act = "Cancelado";
        }



        //Ver quien ha visto su actividad asignada

        $atendido = DB::SELECT("SELECT COUNT(ra.acuse) AS atencion FROM responsables_actividades AS ra
        WHERE idac_actividades = $idac
        AND ra.acuse = 1");
        //dd($atendido);

        //Ver el total de personas asignadas a esa actividad
        $total_at = DB::SELECT("SELECT COUNT(ra.acuse) AS total FROM responsables_actividades AS ra
        WHERE idac_actividades = $idac");

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
                'arse.detalle_a',

            )
            ->where('responsables_actividades.idac_actividades', '=', $idac)
            ->where('responsables_actividades.idu_users', '=', Auth()->user()->idu)
            ->groupBy('seguimientos_actividades.idseac')
            ->get();

        $array_sa = array();

        $ultimo_seg = DB::SELECT("SELECT sa.idseac FROM seguimientos_actividades AS sa
        INNER JOIN responsables_actividades AS ra ON ra.idreac = sa.idreac_responsables_actividades
        WHERE ra.idac_actividades = $idac 
        AND ra.idu_users = $id_user
        ORDER BY sa.idseac DESC LIMIT 1");


        function detalles($idseac, $idarseg, $ultimo)
        {


            if ($idseac == $ultimo) {
                return "<div class='btn-group me-2' role='group' aria-label='Second group'>
                <a href='javascript:void(0)' data-toggle='tooltip' data-id=" . encrypt($idseac) . "  data-original-title='DetallesArchivos' class='btn btn-success btn-sm mt-1 DetallesArchivos'><i class='nav-icon fas fa-eye'></i></a>
                <a class='btn btn-danger mt-1 btn-sm' href=" . route('EliminarSeguimiento', ['idarse' => encrypt($idarseg), 'idseac' => encrypt($idseac)]) . " id='boton_disabled' ><i class='nav-icon fas fa-trash'></i></a></div>";
            } else {
                return  "<a href='javascript:void(0)' data-toggle='tooltip' data-id=" . encrypt($idseac) . "  data-original-title='DetallesArchivos' class='btn btn-success btn-sm mt-1 DetallesArchivos'><i class='nav-icon fas fa-eye'></i></a>";
            }
        }


        foreach ($seguimientos as $seg_ac) {
            $turno = 1;
        }

        foreach ($seguimientos as $seg_ac) {


            array_push($array_sa, array(

                'idseac' => $turno,
                'fecha' => Carbon::parse($seg_ac->fecha)->locale('es')->isoFormat('D MMMM h:mm a'),
                'detalle' => $seg_ac->detalle,
                'estado' => $seg_ac->estado,
                'porcentaje' => $seg_ac->porcentaje,
                'evidencia' => detalles($seg_ac->idseac, $seg_ac->idarseg, $ultimo_seg[0]->idseac),
            ));
            $turno = $turno + 1;
        }

        $json_sa = json_encode($array_sa);


        return view('SeguimientoActividades.Seguimiento')
            ->with('actividades', $actividades[0])
            ->with('resp', $resp[0])
            ->with('json_sa', $json_sa)
            ->with('user', $user[0])
            ->with('now', $now)
            ->with('atendido', $atendido[0])
            ->with('total_at', $total_at[0])
            ->with('max_ai', $max_ai[0])
            ->with('general', $general)
            ->with('archivo1', $archivo1)
            ->with('archivo2', $archivo2)
            ->with('archivo3', $archivo3)
            ->with('est_act', $est_act);
    }

    public function AgregarSeguimiento(Request $request)
    {
        return DB::transaction(function () use ($request) {

            $idseac = $request->idseac;
            $idreac_responsables_actividades = $request->idreac_responsables_actividades;
            $idseac_seguimientos_actividades = $request->idseac_seguimientos_actividades;
            $now = Carbon::now();
            $detalle = $request->detalle;
            $porcentaje = $request->porcentaje;
            $estado = $request->estado;

            $seg_ac = new seguimientosActividades;
            $seg_ac->idreac_responsables_actividades = $idreac_responsables_actividades;
            $seg_ac->fecha = $now;
            $seg_ac->detalle = $request->detalle;
            $seg_ac->porcentaje = $request->porcentaje;
            $seg_ac->estado = $request->estado;

            $seg_ac->save();

            //Insertar archivos en tabla archivos_seguimientos

            $max_size = (int) ini_get('upload_max_filesize') * 10240;
            $user_id = Auth()->user()->idu;
            $files = $request->file('ruta');

            if ($request->hasFile('ruta')) {

                foreach ($files as $index => $file) {
                    if (Storage::putFileAs('/Seguimientos/', $file, date('Ymd_His_') .$file->getClientOriginalName())) {

                        archivosSeguimientos::create([
                            'idseac_seguimientos_actividades' => $idseac_seguimientos_actividades = $seg_ac->idseac,
                            'nombre' => $file->getClientOriginalName(),
                            'ruta' => date('Ymd_His_') . $file->getClientOriginalName(),
                            'detalle_a' => $request->detalle_a[$index],
                        ]);
                    }
                }
            } else {
                archivosSeguimientos::create([
                    'idseac_seguimientos_actividades' => $idseac_seguimientos_actividades = $seg_ac->idseac,
                    'nombre' => 'Sin archivo',
                    'ruta' => 'Sin archivo',
                    'detalle_a' => 'No hay detalles que mostrar',
                ]);
            }



            $consid = responsablesActividades::find($seg_ac->idreac_responsables_actividades);

            Session::flash('message', 'Se le ha dado un nuevo seguimiento a esta actividad');
            return redirect()->route('Seguimiento', ['idac' => encrypt($consid->idac_actividades)]);
        });
    }
    public function DetallesArchivos($idarc)
    {
        $idarc = decrypt($idarc);
        $query = DB::SELECT("SELECT res.idarseg, res.nombre, res.detalle_a, res.ruta, seg.detalle, seg.fecha
        FROM archivos_seguimientos AS res
        INNER JOIN seguimientos_actividades AS seg ON seg.idseac = res.idseac_seguimientos_actividades
        WHERE res.idseac_seguimientos_actividades = $idarc");
        return response()->json($query);
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

        Session::flash('message2', 'Se ha eliminado el seguimiento de actividad');


        return back();
    }
}
