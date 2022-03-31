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
    /* Constructor para verificar permisos de rutas de acuerdo al rol asiginado del usuario */
    public function __construct()
    {
        $this->middleware('can:ver-actividades-asignadas')->only('actividades_asignadas');
        $this->middleware('can:ver-seguimientos')->only('Seguimiento');
    }

    /* Filtrar solo las actividades que se me han asignado */

    public function actividades_asignadas()
    {
        $id_user = Auth()->user()->idu;
        $ar = Auth()->user()->idar_areas;

        /* Obtener las actividades que tenga asignadas el jefe del asistente
       con los datos del jefe */

        if (Auth()->user()->idtu_tipos_usuarios == 4) {

            $asignado = DB::SELECT("SELECT idu, CONCAT(titulo, ' ',nombre, ' ',app, ' ',apm) AS director  FROM users WHERE idar_areas = $ar AND idtu_tipos_usuarios = 2");
            $id = $asignado[0]->idu;
            $dir = $asignado[0]->director;

            $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto, CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
            ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre AS AREA,ra.idu_users,ac.activo, 
            porcentaje(ac.idac, $id) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
            FROM actividades AS ac
            INNER JOIN users AS us ON us.idu = ac.idu_users
            INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
            INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
            LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
            WHERE ra.idu_users = $id AND ac.activo = 1 AND ac.aprobacion = 1
            GROUP BY ac.idac
            ORDER BY ac.fecha_creacion DESC");
        } else {

            /* Obtener las actividades que tenga asignadas el jefe de área */

            $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto, CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
            ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre AS AREA,ra.idu_users,ac.activo, 
            porcentaje(ac.idac, $id_user) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
            FROM actividades AS ac
            INNER JOIN users AS us ON us.idu = ac.idu_users
            INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
            INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
            LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
            WHERE ra.idu_users = $id_user AND ac.activo = 1 AND ac.aprobacion = 1
            GROUP BY ac.idac
            ORDER BY ac.fecha_creacion DESC");
        }

        /* Lo siguiente es para el uso de la libreria ZingGrid para que se muestren en la tabla.
        Declaracion de un array para despues insertar los datos */

        $array = array();

        /* (NOTA: las variables $data y $value de las siguientes 4 funciones,
        se recuperan en una funcion en la base de datos.
        Dandoles el formato requerido para mostrar en la tabla Zing-Grid) */

        /* Funcion para mostrar el porcentaje total de cada actividad y darle formato */

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

        /* Funcion para mostrar los usuarios que han atendido actividad */

        function AB($data)
        {
            if (gettype($data) == "array") {

                return $data['1'][0] . " de " . $data['1'][1];
            } else {
                return 0;
            }
        }

        /* Función para mostrar el porcentaje y se le de formato */

        function C($data)
        {
            if (gettype($data) == "array") {
                return number_format($data['2'], 0, '.', ' ') . '%';
            } else {
                return 0;
            }
        }

        /* Función para mostrar el estado de la actividad */

        function D($status, $end_date, $data, $acuse)
        {
            if (gettype($data) == "array") {
                $data = number_format($data['2'], 0, '.', ' ');
            } else {
                $data = 0;
            }
            $date = Carbon::now()->locale('es_MX');
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

        /* Funcion para ver los botones de la operaciones */

        function ver($idac)
        {
            $id_user = Auth::user()->idu;
            $ar = Auth::user()->idar_areas;
            $tipo = Auth::user()->idtu_tipos_usuarios;
            if ($tipo == 4) {
                $asignado = DB::select("SELECT idu FROM users AS u WHERE u.idtu_tipos_usuarios = 2 AND u.idar_areas = $ar ");
                $id = $asignado[0]->idu;
            } else {
                $asignado = DB::SELECT("SELECT idu FROM users WHERE idar_areas = $ar AND idtu_tipos_usuarios = 2");
                $id = $id_user;
            }

            /* Consulta para ver si la actividad ya fue aceptada por el usuario */

            $ver_acuse = DB::SELECT("SELECT ra.acuse, ra.idreac
            FROM actividades AS ac
            LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
            WHERE ra.idu_users = $id
            AND ra.idac_actividades = $idac");

            /* Si el acuse fue rechazado */

            if ($ver_acuse[0]->acuse == 2) {

                return "<a class='btn btn-sm btn-danger' disabled><i class='nav-icon fas fa-ban'></i></a>";
            }
            /* Si el acuse fue aceptado */

            if ($ver_acuse[0]->acuse == 1) {
                return "<a class='btn btn-success mt-1 btn-sm' id='btn-mostrar' href=" . route('Seguimiento', ['idac' => encrypt($idac)]) . "><i class='nav-icon fas fa-eye'></i></a>";
            } else {

            /* Si aun no he aceptado el acuse y el usuario no es un asistente*/

                $idreac = $ver_acuse[0]->idreac;
                if (Auth()->user()->idtu_tipos_usuarios != 4) {
                    return "<a class='btn btn-success mt-1 btn-sm' id='$idreac' href=" . route('Seguimiento', ['idac' => encrypt($idac)]) . " hidden><i class='nav-icon fas fa-eye'></i></a>
                        <a href='javascript:void(0)' data-toggle='tooltip' data-id=" . encrypt($idac) . "  data-original-title='DetallesAsignacion' class='edit btn btn-primary btn-sm DetallesAsignacion' id='detalle'><i class='nav-icon fas fa-user-check'></i></a>
                        <a class='btn btn-sm btn-danger' id='mensaje' hidden disabled><i class='nav-icon fas fa-ban'></i></a>";
                } else {

            /* Si aun no he aceptado el acuse y el usuario es un asistente*/
            
                    return "<a class='btn btn-success mt-1 btn-sm' id='$idreac' href=" . route('Seguimiento', ['idac' => encrypt($idac)]) . " hidden><i class='nav-icon fas fa-eye'></i></a>
                        <a class='btn btn-sm btn-danger' id='mensaje' hidden disabled><i class='nav-icon fas fa-ban'></i></a>";
                }
            }
        }

        /* Foreach para insertar en el array todos los datos */

        foreach ($consult as $c) {

            $data = recorrer($c->porcentaje);

            array_push($array, array(

                'turno' => $c->turno,
                'fecha_creacion' => Carbon::parse($c->fecha_creacion)->locale('es')->isoFormat('DD [de] MMMM [del] YYYY'),
                'asunto' => $c->asunto,
                'tipo_actividad' => $c->tipo_actividad,
                'descripcion' => $c->descripcion,
                'creador' => $c->creador,
                'periodo' => Carbon::parse($c->fecha_inicio)->locale('es')->isoFormat('D [de] MMMM [del] YYYY [al]') . Carbon::parse($c->fecha_fin)->locale('es')->isoFormat(' D [de] MMMM [del] YYYY'),
                'importancia' => $c->importancia,
                'area' => $c->AREA,
                'recibo' => AB($data),
                'porcentaje' => C($data),
                'estado' =>  D($c->status, $c->fecha_fin, $data, $c->acuse),
                'operaciones' => ver($c->idac),
            ));
        }

        /* Convertir todo el array en formato JSON */

        $json = json_encode($array);

        /* Si es asistente retornar a la vista con los datos de su jefe
        sino continuar normalmente */

        if (Auth()->user()->idtu_tipos_usuarios == 4) {
            return view('SeguimientoActividades.actividades_asignadas')
                ->with('dir', $dir)
                ->with('json', $json);
        } else {
            return view('SeguimientoActividades.actividades_asignadas')
                ->with('json', $json);
        }
    }

    /* Obtener datos de actividades asignadas en base a los filtros seleccionados por fechas.
    NOTA:(Mismas funciones que en actividades_asignadas) */

    public function fecha_actividades_asignadas(Request $request)
    {
        $id_user = Auth()->user()->idu;
        $fecha_orden =  $request->fecha_orden;
        $fechaIni =  $request->fechaIni;
        $fechaFin =  $request->fechaFin;

        $ar = Auth()->user()->idar_areas;

        if (Auth()->user()->idtu_tipos_usuarios == 4) {
            $dir = DB::SELECT("SELECT idu FROM users WHERE idar_areas = $ar AND idtu_tipos_usuarios = 2");
            $id = $dir[0]->idu;

            if ($fecha_orden == 0) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
            ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
            porcentaje(ac.idac, $id) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
            FROM actividades AS ac
            INNER JOIN users AS us ON us.idu = ac.idu_users
            INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
            INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
            LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
            
            WHERE ra.idu_users = $id AND ac.activo = 1 AND ac.aprobacion = 1
            GROUP BY ac.idac
            ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 1 && $fechaIni != null && $fechaFin != null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
            ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users,ac.activo,
            porcentaje(ac.idac, $id) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
            FROM actividades AS ac
            INNER JOIN users AS us ON us.idu = ac.idu_users
            INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
            INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
            LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
           
            WHERE ra.idu_users = $id AND ac.`fecha_inicio` BETWEEN  DATE('$fechaIni') AND DATE('$fechaFin') AND ac.activo = 1
            AND ac.aprobacion = 1
            GROUP BY ac.idac
            ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 1 && $fechaIni != null && $fechaFin == null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
            ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
            porcentaje(ac.idac, $id) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
            FROM actividades AS ac
            INNER JOIN users AS us ON us.idu = ac.idu_users
            INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
            INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
            LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
           
            WHERE ra.idu_users = $id AND ac.`fecha_inicio` >= DATE('$fechaIni') AND ac.activo = 1 AND ac.aprobacion = 1
            GROUP BY ac.idac
            ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 1 && $fechaIni == null && $fechaFin != null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
            ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
            porcentaje(ac.idac, $id) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
            FROM actividades AS ac
            INNER JOIN users AS us ON us.idu = ac.idu_users
            INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
            INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
            LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
           
            WHERE ra.idu_users = $id AND ac.`fecha_inicio` <= DATE('$fechaFin') AND ac.activo = 1 AND ac.aprobacion = 1
            GROUP BY ac.idac
            ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 1 && $fechaIni == null && $fechaFin == null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
            ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
            porcentaje(ac.idac, $id) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
            FROM actividades AS ac
            INNER JOIN users AS us ON us.idu = ac.idu_users
            INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
            INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
            LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
           
            WHERE ra.idu_users = $id AND ac.activo = 1 AND ac.aprobacion = 1
            GROUP BY ac.idac
            ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 2 && $fechaIni != null && $fechaFin != null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
                ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
                porcentaje(ac.idac, $id) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
                FROM actividades AS ac
                INNER JOIN users AS us ON us.idu = ac.idu_users
                INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
                INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
                LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
               
                WHERE ra.idu_users = $id AND ac.`fecha_fin` BETWEEN  DATE('$fechaIni') AND DATE('$fechaFin') AND ac.activo = 1
                AND ac.aprobacion = 1
                GROUP BY ac.idac
                ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 2 && $fechaIni != null && $fechaFin == null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
                    ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
                    porcentaje(ac.idac, $id) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
                    FROM actividades AS ac
                    INNER JOIN users AS us ON us.idu = ac.idu_users
                    INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
                    INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
                    LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
                   
                    WHERE ra.idu_users = $id AND ac.`fecha_fin` >= DATE('$fechaIni') AND ac.activo = 1 AND ac.aprobacion = 1
                    GROUP BY ac.idac
                    ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 2 && $fechaIni == null && $fechaFin != null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
                    ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users,ac.activo, 
                    porcentaje(ac.idac, $id) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
                    FROM actividades AS ac
                    INNER JOIN users AS us ON us.idu = ac.idu_users
                    INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
                    INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
                    LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
                   
                    WHERE ra.idu_users = $id AND ac.`fecha_fin` <= DATE('$fechaFin') AND ac.activo = 1 AND ac.aprobacion = 1
                    GROUP BY ac.idac
                    ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 2 && $fechaIni == null && $fechaFin == null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
                    ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users,ac.activo, 
                    porcentaje(ac.idac, $id) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
                    FROM actividades AS ac
                    INNER JOIN users AS us ON us.idu = ac.idu_users
                    INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
                    INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
                    LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
                   
                    WHERE ra.idu_users = $id AND ac.activo = 1 AND ac.aprobacion = 1
                    GROUP BY ac.idac
                    ORDER BY ac.fecha_creacion DESC");
            }
        } else {




            if ($fecha_orden == 0) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
        ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
        porcentaje(ac.idac, $id_user) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
        FROM actividades AS ac
        INNER JOIN users AS us ON us.idu = ac.idu_users
        INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
        INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
        LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
        
        WHERE ra.idu_users = $id_user AND ac.activo = 1 AND ac.aprobacion = 1
        GROUP BY ac.idac
        ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 1 && $fechaIni != null && $fechaFin != null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
        ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
        porcentaje(ac.idac, $id_user) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
        FROM actividades AS ac
        INNER JOIN users AS us ON us.idu = ac.idu_users
        INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
        INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
        LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
       
        WHERE ra.idu_users = $id_user AND ac.`fecha_inicio` BETWEEN  DATE('$fechaIni') AND DATE('$fechaFin') AND ac.activo = 1
        AND ac.aprobacion = 1
        GROUP BY ac.idac
        ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 1 && $fechaIni != null && $fechaFin == null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
        ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
        porcentaje(ac.idac, $id_user) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
        FROM actividades AS ac
        INNER JOIN users AS us ON us.idu = ac.idu_users
        INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
        INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
        LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
       
        WHERE ra.idu_users = $id_user AND ac.`fecha_inicio` >= DATE('$fechaIni') AND ac.activo = 1 AND ac.aprobacion = 1
        GROUP BY ac.idac
        ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 1 && $fechaIni == null && $fechaFin != null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
        ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
        porcentaje(ac.idac, $id_user) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
        FROM actividades AS ac
        INNER JOIN users AS us ON us.idu = ac.idu_users
        INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
        INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
        LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
       
        WHERE ra.idu_users = $id_user AND ac.`fecha_inicio` <= DATE('$fechaFin') AND ac.activo = 1 AND ac.aprobacion = 1
        GROUP BY ac.idac
        ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 1 && $fechaIni == null && $fechaFin == null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
        ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
        porcentaje(ac.idac, $id_user) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
        FROM actividades AS ac
        INNER JOIN users AS us ON us.idu = ac.idu_users
        INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
        INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
        LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
       
        WHERE ra.idu_users = $id_user AND ac.activo = 1 AND ac.aprobacion = 1
        GROUP BY ac.idac
        ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 2 && $fechaIni != null && $fechaFin != null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
            ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
            porcentaje(ac.idac, $id_user) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
            FROM actividades AS ac
            INNER JOIN users AS us ON us.idu = ac.idu_users
            INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
            INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
            LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
           
            WHERE ra.idu_users = $id_user AND ac.`fecha_fin` BETWEEN  DATE('$fechaIni') AND DATE('$fechaFin') AND ac.activo = 1
            AND ac.aprobacion = 1
            GROUP BY ac.idac
            ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 2 && $fechaIni != null && $fechaFin == null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
                ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
                porcentaje(ac.idac, $id_user) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
                FROM actividades AS ac
                INNER JOIN users AS us ON us.idu = ac.idu_users
                INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
                INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
                LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
               
                WHERE ra.idu_users = $id_user AND ac.`fecha_fin` >= DATE('$fechaIni') AND ac.activo = 1 AND ac.aprobacion = 1
                GROUP BY ac.idac
                ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 2 && $fechaIni == null && $fechaFin != null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
                ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
                porcentaje(ac.idac, $id_user) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
                FROM actividades AS ac
                INNER JOIN users AS us ON us.idu = ac.idu_users
                INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
                INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
                LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
               
                WHERE ra.idu_users = $id_user AND ac.`fecha_fin` <= DATE('$fechaFin') AND ac.activo = 1 AND ac.aprobacion = 1
                GROUP BY ac.idac
                ORDER BY ac.fecha_creacion DESC");
            }
            if ($fecha_orden == 2 && $fechaIni == null && $fechaFin == null) {
                $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
                ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as area,ra.idu_users, ac.activo,
                porcentaje(ac.idac, $id_user) AS porcentaje, ac.descripcion,ac.status, ra.acuse, ta.nombre AS tipo_actividad
                FROM actividades AS ac
                INNER JOIN users AS us ON us.idu = ac.idu_users
                INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
                INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
                LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
               
                WHERE ra.idu_users = $id_user AND ac.activo = 1 AND ac.aprobacion = 1
                GROUP BY ac.idac
                ORDER BY ac.fecha_creacion DESC");
            }
        }

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
            $date = Carbon::now()->locale('es')->isoFormat("Y-MM-DD");
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
            $id_user = Auth::user()->idu;
            $ar = Auth::user()->idar_areas;
            $tipo = Auth::user()->idtu_tipos_usuarios;
            if ($tipo == 4) {
                $asignado = DB::select("SELECT idu FROM users AS u WHERE u.idtu_tipos_usuarios = 2 AND u.idar_areas = $ar ");
                $id = $asignado[0]->idu;
            } else {
                $asignado = DB::SELECT("SELECT idu FROM users WHERE idar_areas = $ar AND idtu_tipos_usuarios = 2");
                $id = $id_user;
            }
            $ver_acuse = DB::SELECT("SELECT ra.acuse, ra.idreac
            FROM actividades AS ac
            LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
            WHERE ra.idu_users = $id
            AND ra.idac_actividades = $idac");

            if ($ver_acuse[0]->acuse == 2) {

                return "<a class='btn btn-sm btn-danger' disabled><i class='nav-icon fas fa-ban'></i></a>";
            }

            if ($ver_acuse[0]->acuse == 1) {
                return "<a class='btn btn-success mt-1 btn-sm' id='btn-mostrar' href=" . route('Seguimiento', ['idac' => encrypt($idac)]) . "><i class='nav-icon fas fa-eye'></i></a>";
            } else {
                $idreac = $ver_acuse[0]->idreac;
                if (Auth()->user()->idtu_tipos_usuarios != 4) {
                    return "<a class='btn btn-success mt-1 btn-sm' id='$idreac' href=" . route('Seguimiento', ['idac' => encrypt($idac)]) . " hidden><i class='nav-icon fas fa-eye'></i></a>
                        <a href='javascript:void(0)' data-toggle='tooltip' data-id=" . encrypt($idac) . "  data-original-title='DetallesAsignacion' class='edit btn btn-primary btn-sm DetallesAsignacion' id='detalle'><i class='nav-icon fas fa-user-check'></i></a>
                        <a class='btn btn-sm btn-danger' id='mensaje' hidden disabled><i class='nav-icon fas fa-ban'></i></a>";
                } else {
                    return "<a class='btn btn-success mt-1 btn-sm' id='$idreac' href=" . route('Seguimiento', ['idac' => encrypt($idac)]) . " hidden><i class='nav-icon fas fa-eye'></i></a>
                        <a class='btn btn-sm btn-danger' id='mensaje' hidden disabled><i class='nav-icon fas fa-ban'></i></a>";
                }
            }
        }

        foreach ($consult as $c) {

            $data = recorrer($c->porcentaje);

            array_push($array, array(

                'turno' => $c->turno,
                'fecha_creacion' => Carbon::parse($c->fecha_creacion)->locale('es')->isoFormat('DD [de] MMMM [del] YYYY'),
                'asunto' => $c->asunto,
                'tipo_actividad' => $c->tipo_actividad,
                'descripcion' => $c->descripcion,
                'creador' => $c->creador,
                'periodo' => Carbon::parse($c->fecha_inicio)->locale('es')->isoFormat('D [de] MMMM [del] YYYY [al]') . Carbon::parse($c->fecha_fin)->locale('es')->isoFormat(' D [de] MMMM [del] YYYY'),
                'importancia' => $c->importancia,
                'area' => $c->area,
                'recibo' => AB($data),
                'porcentaje' => C($data),
                'estado' =>  D($c->status, $c->fecha_fin, $data, $c->acuse),
                'operaciones' => ver($c->idac),
            ));
        }
        $json = json_encode($array);
        return $json;
        return response()->json($json);
    }

    /* Si se acepta la actividad camibar el estado del acuse */

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
        } else {
            Session::flash('message2', 'Contraseña incorrecta, favor de verificar que la contraseña este escrita correctamente');
        }
    }

    /* Si se rechaaza la actividad camibar el estado del acuse */

    public function rechazarActividad(Request $request)
    {
        $idac = decrypt($request->id_a);
        $razon_r = $request->rechazo;
        $id_user = Auth()->user()->idu;

        $rechazar = DB::UPDATE("UPDATE responsables_actividades SET
                acuse = 2, fecha_acuse = CURDATE(), razon_rechazo = '$razon_r'
                WHERE idu_users = $id_user AND idac_actividades = $idac");
        Session::flash('rechazo', 'Usted ha rechazado la actividad, por lo que no se ha bloqueado la actividad, para desbloquear la actividad deberá ponerse en contacto con el creador de la actividad');

        return response()->json('aceptado');
    }

    /* Obtener detalles de la actividad para mostrarlos en el modal por medio de Ajax */

    public function DetallesAsignacion($idac)
    {
        $idac = decrypt($idac);
        $id_user = Auth()->user()->idu;

        $actividad = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto, ac.descripcion,
        CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador, ac.comunicado, res_act.razon_activacion,
        ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre AS nombre_area,
        ac.status, porcentaje(ac.idac,$id_user) AS porcentaje, res_act.idu_users
        FROM actividades AS ac
        INNER JOIN users AS us ON us.idu = ac.idu_users
        INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
        INNER JOIN responsables_actividades AS res_act ON res_act.idac_actividades = ac.idac
        WHERE ac.idac = $idac
        AND res_act.idu_users = $id_user");

        return response()->json($actividad);
    }
    
    /* Funcion para ver los seguimientos que se le han dado a esa actividad asigada */

    public function Seguimiento($idac)
    {
    /* Desencriptar el id de la actividad que se esta consulutando */
        $idac = decrypt($idac);
        $id_user = Auth()->user()->idu;
        $ar = Auth::user()->idar_areas;
        $tipo = Auth::user()->idtu_tipos_usuarios;

        $director = DB::SELECT("SELECT CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS director FROM users AS us WHERE idtu_tipos_usuarios = 2 AND idar_areas = $ar");
        $dir = $director[0]->director;

    /* Verificar si es asistente o director y obtener detalles de esa actividad */
        if ($tipo == 4) {
            $asignado = DB::select("SELECT idu FROM users AS u WHERE u.idtu_tipos_usuarios = 2 AND u.idar_areas = $ar ");
            $id = $asignado[0]->idu;

            $actividades = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto, ac.descripcion,
            CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador, ac.comunicado, res_act.razon_activacion,
            ac.fecha_inicio, ac.fecha_fin, ac.importancia, ar.nombre as nombre_area,
            ac.archivo1, ac.archivo2, ac.archivo3, ac.link1, ac.link2, ac.link3, ta.nombre as tipo_act,
            ac.status, porcentaje(ac.idac,$id) AS porcentaje
            FROM actividades AS ac
            INNER JOIN users AS us ON us.idu = ac.idu_users
            INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
            INNER JOIN responsables_actividades AS res_act ON res_act.idac_actividades = ac.idac
            INNER JOIN tipos_actividades AS ta ON ta.idtac = ac.idtac_tipos_actividades
            WHERE ac.idac = $idac");
        } else {
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
        }

        $archivo1 = null;
        $archivo2 = null;
        $archivo3 = null;

        /* Darle otro formato al nombre de los archivos almacenados  */

        if ($actividades[0]->archivo1 != 'Sin archivo') {
            $archivo1 = explode("_", $actividades[0]->archivo1)[2];
        }
        if ($actividades[0]->archivo2 != 'Sin archivo') {
            $archivo2 = explode("_", $actividades[0]->archivo2)[2];
        }
        if ($actividades[0]->archivo3 != 'Sin archivo') {
            $archivo3 = explode("_", $actividades[0]->archivo3)[2];
        }

        /* Extraer porcentaje general de la actividad */
        $general = explode('*', $actividades[0]->porcentaje)[2];
        $general = number_format($general, 0);

        $end_date = $actividades[0]->fecha_fin;


        if ($tipo == 4) {
            $asignado = DB::select("SELECT idu FROM users AS u WHERE u.idtu_tipos_usuarios = 2 AND u.idar_areas = $ar ");
            $id = $asignado[0]->idu;

            //Obtener datos del usuario
            $user = DB::table('users')
                ->join('roles', 'roles.id', '=', 'users.idtu_tipos_usuarios')
                ->join('areas', 'areas.idar', '=', 'users.idar_areas')
                ->select(
                    'users.idu',
                    'users.titulo',
                    'users.nombre',
                    'users.app',
                    'users.apm',
                    'roles.name as tipo_usuario',
                    'areas.nombre as nombre_areas',
                    'areas.idar',
                    'areas.nombre as area',
                )
                ->where('users.idu', '=', $id)
                ->get();
        } else {
            //Obtener datos del usuario
            $user = DB::table('users')
                ->join('roles', 'roles.id', '=', 'users.idtu_tipos_usuarios')
                ->join('areas', 'areas.idar', '=', 'users.idar_areas')
                ->select(
                    'users.idu',
                    'users.titulo',
                    'users.nombre',
                    'users.app',
                    'users.apm',
                    'roles.name as tipo_usuario',
                    'areas.nombre as nombre_areas',
                    'areas.idar',
                    'areas.nombre as area',
                )
                ->where('users.idu', '=', Auth()->user()->idu)
                ->get();
        }

        //Obtener la fecha actual
        $now = Carbon::now();

        //Obtener el responsable que le esta dando seguimiento a la actividad
        if ($tipo != 4) {
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
            WHERE idac_actividades = $idac AND idu_users = $id_user");
        } else {
            $resp = DB::table('responsables_actividades')
                ->join('actividades', 'actividades.idac', '=', 'responsables_actividades.idac_actividades')
                ->select(
                    'responsables_actividades.idreac',
                    'responsables_actividades.idu_users',
                    'responsables_actividades.idac_actividades',
                    'responsables_actividades.acuse',
                )
                ->where('responsables_actividades.idu_users', '=', $id)
                ->where('idac', $idac)
                ->get();

            $max_ai = DB::SELECT("SELECT MAX(seg.porcentaje) AS avance_i
            FROM responsables_actividades AS res
            JOIN seguimientos_actividades AS seg ON seg.idreac_responsables_actividades = res.idreac
            WHERE idac_actividades = $idac AND idu_users = $id");
        }

        //ver el estado de la actividad
        $date = Carbon::now()->locale('es')->isoFormat("Y-MM-DD");

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

        //Ver cuantos han visto su actividad asignada

        $atendido = DB::SELECT("SELECT COUNT(ra.acuse) AS atencion FROM responsables_actividades AS ra
        WHERE idac_actividades = $idac
        AND ra.acuse = 1");

        //Ver el total de personas asignadas a esa actividad
        $total_at = DB::SELECT("SELECT COUNT(ra.acuse) AS total FROM responsables_actividades AS ra
        WHERE idac_actividades = $idac");

        //Marca que el usuario le ha empezado a dar seguimiento a su actividad en el campo acuse = 1

        $consulta = $resp[0]->idreac;
        $acuse = $resp[0]->acuse;
        if ($acuse == '0') {
            DB::UPDATE("UPDATE responsables_actividades SET acuse = '1' WHERE idreac = $consulta");
        }

        if ($tipo == 4) {
        //Obtener los seguimientos que se le ha dado a la actividad asignada
            $asignado = DB::select("SELECT idu FROM users AS u WHERE u.idtu_tipos_usuarios = 2 AND u.idar_areas = $ar ");
            $id = $asignado[0]->idu;

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
                ->where('responsables_actividades.idu_users', '=', $id)
                ->groupBy('seguimientos_actividades.idseac')
                ->get();

            $array_sa = array();

            $ultimo_seg = DB::SELECT("SELECT sa.idseac, sa.archivo_fin  FROM seguimientos_actividades AS sa
            INNER JOIN responsables_actividades AS ra ON ra.idreac = sa.idreac_responsables_actividades
            WHERE ra.idac_actividades = $idac 
            AND ra.idu_users = $id
            ORDER BY sa.idseac DESC LIMIT 1");
        } else {
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

            $ultimo_seg = DB::SELECT("SELECT sa.idseac, sa.archivo_fin FROM seguimientos_actividades AS sa
            INNER JOIN responsables_actividades AS ra ON ra.idreac = sa.idreac_responsables_actividades
            WHERE ra.idac_actividades = $idac 
            AND ra.idu_users = $id_user
            ORDER BY sa.idseac DESC LIMIT 1");
        }

        /* Funcion para ver los botones de los seguimientos realizados */

        function detalles($idseac, $idarseg, $ultimo, $archivo_fin, $idac)
        {
            /* Si es el ultimo seguimiento que se ha realizado */
            if ($idseac == $ultimo) {
                /* y si ya se subio el oficio de termnino */
                if ($archivo_fin != null) {
                    /* la persona logeada sea un usuario normal (Jefe) */
                    if (Auth()->user()->idtu_tipos_usuarios == 2) {
                        return "<div class='btn-group me-2' role='group' aria-label='Second group'>
                            <a href='javascript:void(0)' data-toggle='tooltip' data-id=" . encrypt($idseac) . "  data-original-title='DetallesArchivos' class='btn btn-success btn-sm mt-3 DetallesArchivos'><i class='nav-icon fas fa-eye'></i></a>
                            &nbsp;<a download='archivo-finalizacion' href=" . asset("archivos/Seguimientos/$archivo_fin") . " class='ArchivoTermino btn btn-dark btn-sm mt-3'><i class='nav-icon fas fa-file-pdf'></i></a>
                            &nbsp;<a class='btn btn-danger mt-3 btn-sm' href=" . route('EliminarSeguimiento', ['idarse' => encrypt($idarseg), 'idseac' => encrypt($idseac), 'idac' => encrypt($idac)]) . " id='boton_disabled' ><i class='nav-icon fas fa-trash'></i></a></div>";
                    } else {
                        /* Si es cualquier otro tipo de usuario */
                        return "<div class='btn-group me-2' role='group' aria-label='Second group'>
                            <a href='javascript:void(0)' data-toggle='tooltip' data-id=" . encrypt($idseac) . "  data-original-title='DetallesArchivos' class='btn btn-success btn-sm mt-3 DetallesArchivos'><i class='nav-icon fas fa-eye'></i></a>
                            &nbsp;<a download='archivo-finalizacion' href=" . asset("archivos/Seguimientos/$archivo_fin") . " class='ArchivoTermino btn btn-dark btn-sm mt-3'><i class='nav-icon fas fa-file-pdf'></i></a>";
                    }
                } else {
                    /* Si aún no se sube el oficio de termino y el usuario es: (Jefe) */
                    if (Auth()->user()->idtu_tipos_usuarios == 2) {
                        return "<div class='btn-group me-2' role='group' aria-label='Second group'>
                        <a href='javascript:void(0)' data-toggle='tooltip' data-id=" . encrypt($idseac) . "  data-original-title='DetallesArchivos' class='btn btn-success btn-sm mt-1 DetallesArchivos'><i class='nav-icon fas fa-eye'></i></a>
                        <a class='btn btn-danger mt-1 btn-sm' href=" . route('EliminarSeguimiento', ['idarse' => encrypt($idarseg), 'idseac' => encrypt($idseac), 'idac' => encrypt($idac)]) . " id='boton_disabled' ><i class='nav-icon fas fa-trash'></i></a></div>";
                    } else {
                        /* Si aún no se sube el oficio de termino y el usuario no es: (Jefe) */
                        return "<div class='btn-group me-2' role='group' aria-label='Second group'>
                        <a href='javascript:void(0)' data-toggle='tooltip' data-id=" . encrypt($idseac) . "  data-original-title='DetallesArchivos' class='btn btn-success btn-sm mt-1 DetallesArchivos'><i class='nav-icon fas fa-eye'></i></a>";
                    }
                }
            } else {
            /* Si aun no es el ultimo seguimiento  */
                return  "<a href='javascript:void(0)' data-toggle='tooltip' data-id=" . encrypt($idseac) . "  data-original-title='DetallesArchivos' class='btn btn-success btn-sm mt-1 DetallesArchivos'><i class='nav-icon fas fa-eye'></i></a>";
            }
        }

        /* Contador para marcar el total de seguimientos dados */

        foreach ($seguimientos as $seg_ac) {
            $turno = 1;
        }

        /* Procesar los datos transformando en formato JSON para tabla Zing-Grid */

        foreach ($seguimientos as $seg_ac) {

            array_push($array_sa, array(

                'idseac' => $turno,
                'fecha' => Carbon::parse($seg_ac->fecha)->locale('es')->isoFormat('DD MMMM h:mm a'),
                'detalle' => $seg_ac->detalle,
                'estado' => $seg_ac->estado,
                'porcentaje' => $seg_ac->porcentaje,
                'evidencia' => detalles($seg_ac->idseac, $seg_ac->idarseg, $ultimo_seg[0]->idseac, $ultimo_seg[0]->archivo_fin, $idac),
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
            ->with('est_act', $est_act)
            ->with('ultimo_seg', $ultimo_seg)
            ->with('idac', $idac)
            ->with('dir', $dir);
    }

    /* Funcion para guardar el nuevo seguimiento */

    public function AgregarSeguimiento(Request $request)
    {
        return DB::transaction(function () use ($request) {

            /* Dando nombres a las variables que llegan del request */
            $idseac = $request->idseac;
            $idac = $request->idac;
            $idreac_responsables_actividades = $request->idreac_responsables_actividades;
            $idseac_seguimientos_actividades = $request->idseac_seguimientos_actividades;
            /* Obtener fecha actual */
            $now = Carbon::now();
            $detalle = $request->detalle;
            $porcentaje = $request->porcentaje;
            $estado = $request->estado;

            /* Si se subieron archivos en el seguimiento y el nombre tiene espacios 
            en blanco eliminarlos para evitar un error de compatibidad del navegador
            al descargar, ademas concatenando la fecha al nombre. */
            if ($request->file('archivo_fin')) {
                $searchString = " ";
                $replaceString = "-";
                $file_fin = $request->file('archivo_fin');
                $name_arcfin = date('YmdHis_') . $file_fin->getClientOriginalName();
                $name_arcfin = str_replace($searchString, $replaceString, $name_arcfin);
            }
            /* Guardar datos de la tabla de seguimientos_actividades*/
            $seg_ac = new seguimientosActividades;
            $seg_ac->idreac_responsables_actividades = $idreac_responsables_actividades;
            $seg_ac->fecha = $now;
            $seg_ac->detalle = $request->detalle;
            $seg_ac->porcentaje = $request->porcentaje;
            /* Si ya es el ultimo seguimiento y recibe el oficio de termino guardar en ruta indicada */
            if ($request->hasFile('archivo_fin')) {
                if (Storage::putFileAs('/Seguimientos/', $file_fin, $name_arcfin)) {
                    $id_user = Auth::user()->idu;
                    $seg_ac->archivo_fin = $name_arcfin;
                    DB::UPDATE("UPDATE responsables_actividades SET estado_act = 'Completada'
                    WHERE idu_users = $id_user AND idac_actividades = $idac");
                }
            }

            $seg_ac->estado = $request->estado;
            $seg_ac->save();

            /* Guardar datos de la tabla de archivos_seguimientos*/

            $max_size = (int)ini_get('upload_max_filesize') * 10240;
            $user_id = Auth()->user()->idu;
            $files = $request->file('ruta');

            if ($request->hasFile('ruta')) {
                /* Si se subieron archivos guardar con su descripción */
                foreach ($files as $index => $file) {
                    if (Storage::putFileAs('/Seguimientos/', $file, date('Ymd_His_') . $file->getClientOriginalName())) {

                        archivosSeguimientos::create([
                            'idseac_seguimientos_actividades' => $idseac_seguimientos_actividades = $seg_ac->idseac,
                            'nombre' => $file->getClientOriginalName(),
                            'ruta' => date('Ymd_His_') . $file->getClientOriginalName(),
                            'detalle_a' => $request->detalle_a[$index],
                        ]);
                    }
                }
            } else {
                /* Si no se subieron archivos */
                archivosSeguimientos::create([
                    'idseac_seguimientos_actividades' => $idseac_seguimientos_actividades = $seg_ac->idseac,
                    'nombre' => 'Sin archivo',
                    'ruta' => 'Sin archivo',
                    'detalle_a' => 'No hay detalles que mostrar',
                ]);
            }

            $consid = responsablesActividades::find($seg_ac->idreac_responsables_actividades);

            /*Validación porcentaje general para modificar status en tabla actividades*/
            $consul = DB::SELECT("SELECT 
            porcentaje(ac.idac, 0) AS porcentaje
            FROM actividades AS ac
            WHERE ac.idac = $idac");

            $porcentaje = number_format(explode("*", $consul[0]->porcentaje)[2], 0, '.', ' ');

            if ($porcentaje == 100) {

                DB::UPDATE("UPDATE actividades SET status = 2 
                WHERE idac = $idac");
            }

            Session::flash('message', 'Se le ha dado un nuevo seguimiento a esta actividad');
            return redirect()->route('Seguimiento', ['idac' => encrypt($consid->idac_actividades)]);
        });
    }

    /* Ver los detalles de archivos subidos en base al seleccionado (MOdal con Ajax) */
    public function DetallesArchivos($idarc)
    {
        $idarc = decrypt($idarc);
        $query = DB::SELECT("SELECT res.idarseg, res.nombre, res.detalle_a, res.ruta, seg.detalle, seg.fecha
        FROM archivos_seguimientos AS res
        INNER JOIN seguimientos_actividades AS seg ON seg.idseac = res.idseac_seguimientos_actividades
        WHERE res.idseac_seguimientos_actividades = $idarc");
        return response()->json($query);
    }

    /* Funcion para eliminar un seguimiento */

    public function EliminarSeguimiento($idarseg, $idseac, $idac)
    {
        $idarseg = decrypt($idarseg);
        $idseac = decrypt($idseac);
        $idac = decrypt($idac);

        /* Eliminar archivos del servidor */
        $fileToDelete = DB::SELECT("SELECT ruta FROM archivos_seguimientos 
        WHERE idseac_seguimientos_actividades = $idseac");
         
         foreach($fileToDelete as $ftd){
             $path = "/Seguimientos/".$ftd->ruta;
             Storage::disk('local')->delete($path);
             
        }
        
        /* Eliminar registros en la BD */

        DB::DELETE("DELETE FROM archivos_seguimientos
        where idseac_seguimientos_actividades =$idseac");

        DB::DELETE("DELETE FROM seguimientos_actividades
        where idseac =$idseac");

        $consul = DB::SELECT("SELECT 
        porcentaje(ac.idac, 0) AS porcentaje
        FROM actividades AS ac
        WHERE ac.idac = $idac");

        $porcentaje = number_format(explode("*", $consul[0]->porcentaje)[2], 0, '.', ' ');

        if ($porcentaje < 100) {

            DB::UPDATE("UPDATE actividades SET status = 1 
            WHERE idac = $idac");
        }

        Session::flash('message2', 'Se ha eliminado el seguimiento de actividad');
        return back();
    }
}
