<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Actividades;
use App\Models\ResponsablesActividades;
use App\Models\Users;
use App\Models\SeguimientosActividades;
use DB;
use Arr;
use PDF;

class ActividadesController extends Controller
{
    public function reporte_actividades(){

        $us_id = \Auth()->User()->idu;

        $consult = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
        CONCAT(ac.fecha_inicio, ' al ', ac.fecha_fin) AS periodo, ac.importancia, ar.nombre, ac.activo, ra.acuse, ra.idu_users, ac.descripcion, porcentaje(ac.idac,$us_id) AS porcentaje
        FROM actividades AS ac
        INNER JOIN users AS us ON us.idu = ac.idu_users
        INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
        LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
        LEFT JOIN seguimientos_actividades AS sa ON sa.idreac_responsables_actividades = idreac
        GROUP BY ac.idac
        ORDER BY ac.fecha_creacion DESC");

        $array = array();

        function recorrer($value){
            if (gettype($value) == "string") {
                $val = explode('*', $value);
                $arr = array('1'=> explode('-', $val[0]),'2'=>$val[1]);
            }else{
                $arr = null;
            }
            return $arr;
        }

        function btn($idac, $activo){

            return "<a target='_blank' class='btn btn-success btn-sm' onclick=window.open(this.href,this.target,width=600,height=800); href=".route('Detalles', ['id' => encrypt($idac)]) .">Detalle</a>";

        }

        function AB($data){

            if(gettype($data) == "array"){

                return $data['1'][0]." de ".$data['1'][1];
            }else{
                return 0;
            }
        }

        function C($data){

            if(gettype($data) == "array"){

                return number_format($data['2'], 0, '.', ' ').'%';
            }else{
                return 0;
            }

        }

        foreach($consult as $c){

            $data = recorrer($c->porcentaje);

            array_push($array, array('idac' => $c->idac,
                                    'turno' => $c->turno,
                                    'fecha_creacion' => $c->fecha_creacion,
                                    'asunto' => $c->asunto,
                                    'descripcion' => $c->descripcion,
                                    'creador' => $c->creador,
                                    'periodo' => $c->periodo,
                                    'importancia' => $c->importancia,
                                    'nombre' => $c->nombre,
                                    'activo' => $c->activo,
                                    'acuse' => $c->acuse,
                                    'idu_users' => $c->idu_users,
                                    'AB' => AB($data),
                                    'C' =>  C($data),
                                    'operaciones' => btn($c->idac, $c->activo),
                                    ));
        }

        $json = json_encode($array);

        return view('Actividades.reporte')
        ->with('json', $json);
    }

    public function Detalles($idac){
        $idac = decrypt($idac);
        $query = DB::SELECT("SELECT res.idu_users, ar.nombre AS nombre_ar, CONCAT(us.titulo,'.', us.nombre, ' ', us.app, ' ', us.apm) AS nombre_us, 
        res.acuse, res.idreac, seg.estado, MAX(seg.porcentaje) AS porcentaje, razon_rechazo
        FROM responsables_actividades AS res
        JOIN users AS us ON us.idu = res.idu_users
        JOIN areas AS ar ON ar.idar = us.idar_areas
        JOIN seguimientos_actividades AS seg ON seg.idreac_responsables_actividades = res.idreac
        WHERE idac_actividades = $idac
        GROUP BY idu_users");

        $boton = DB::table('responsables_actividades as res')
                    ->select(DB::raw('IF(COUNT(res.acuse) = 0, 0 , 1) AS boton'))
                    ->where([
                        ['res.idac_actividades', '=' , $idac],
                        ['res.acuse', '=' , 1],
                        ])
                    ->first();


        $array = array();

        function recorrer($value){
            $arr = (gettype($value) == "string") ? explode('-', $value) : null;
            return $arr;
        }


        function btn($idac,$data,$rechazo){
            if($data == 0){
                return ("No existen detalles");
           
            }else if($data == 1){
                return "<a href=".route('detallesSeguimiento', encrypt($idac))."><button type='button' class='btn btn-success'>Ver detalle</button></a>   ";
            }else if($data == 2){
                return ("Razon: $rechazo" );
            }
        }

     //  function C($data){

       //     if(gettype($data) == "array"){

         //       return number_format($data[2], 2, '.', ' ').'%';
           // }else{
             //   return 0.00;
            //}

        //}

          // $data = recorrer($c->porcentaje);


        function Acuse($data){


            if ($data == 1){
                 $acuse = "Recibido";
             }else if($data == 2){
                 $acuse = "Rechazado";
             }else{
                 $acuse = "No recibido";
             }
             return $acuse;
          }

        foreach($query as $c){

             $data = Acuse($c->acuse);

            array_push($array, array('nombre_us' => $c->nombre_us,
                                    'nombre_ar' => $c->nombre_ar,
                                    'porcentaje' =>  $c->porcentaje.'%',
                                    'estado' => $c->estado,
                                    'acuse' => $data,
                                    'operaciones' => btn($c->idreac,$c->acuse,$c->razon_rechazo),
                                    ));
        }

        $json = json_encode($array);

        return view('Actividades.reporte_detalles')

        ->with('json', $json)
        ->with('idac', $idac)
        ->with('boton', $boton);

    }

    public function pdf($idac){


        $idac = decrypt($idac);

        $data = DB::SELECT("SELECT CONCAT(us.titulo,' ',us.nombre,' ',us.app,' ',us.apm) AS nombre ,res.fecha_acuse, CONCAT(ar.nombre,'/', ta.nombre) AS area,
        ac.asunto , ac.descripcion , ac.comunicado, ac.fecha_creacion , ac.fecha_inicio, ac.fecha_fin, SUBSTRING(res.firma, 1, 20) AS firma, SUBSTRING(res.firma, 21, 46) AS firma2
        FROM responsables_actividades AS res
        JOIN users AS us ON us.idu = res.idu_users
        JOIN areas AS ar ON ar.idar = us.idar_areas
        JOIN tipos_areas AS ta ON ta.idtar = ar.idtar
        JOIN actividades AS ac ON ac.idac = res.idac_actividades
        WHERE idac_actividades = $idac
        AND res.acuse = 1");

        $pdf = PDF::loadView('Actividades.pdf', compact('data'));
        return $pdf->stream('PDF de actividades seguimientos.pdf');
    }


    public function detallesSeguimiento($idac)
	{
        $idac = decrypt($idac);

        $idActvidad = ResponsablesActividades::where('idreac',$idac)->select('idac_actividades')->first();
        $idActvidad = encrypt($idActvidad->idac_actividades);
        $consult = DB::SELECT("SELECT seg.idseac, seg.fecha, seg.detalle, seg.porcentaje, seg.estado, us.nombre, arch.ruta, act.asunto, arch.ruta
        FROM seguimientos_actividades AS seg
        INNER JOIN responsables_actividades AS re ON re.idreac = seg.idreac_responsables_actividades
        INNER JOIN users AS us ON us.idu = re.idu_users
        INNER JOIN actividades AS act ON re.idac_actividades = act.idac
        INNER JOIN archivos_seguimientos AS arch ON arch.idseac_seguimientos_actividades = seg.idseac
            WHERE idreac_responsables_actividades = $idac
            GROUP BY idseac");

        $array = array();

        function recorrer($value){
          $arr = (gettype($value) == "string") ? explode('-', $value) : null;
            return $arr;
        }
        function btn($idac,$ruta){
            if ($ruta == "Sin archivo"){
                return "Sin archivos";
            }else{
            return "
                <a href='javascript:void(0)' data-toggle='tooltip' data-id=".encrypt($idac)."  data-original-title='DetallesArchivos' class='edit btn btn-success btn-sm DetallesArchivos'>Archivos</a>";
                }
            }
        foreach($consult as $c){

         // $data = recorrer($c->porcentaje);

          array_push($array, array('idseac' => $c->idseac,
                             'fecha' => $c->fecha,
                             'detalle' =>  $c->detalle,
                             'estado' => $c->estado,
                             'porcentaje' => $c->porcentaje.'%',
                             'operaciones' => btn($c->idseac,$c->ruta),
                             ));
        }
        $json = json_encode($array);

            return view('SeguimientoActividades.detallesSeguimiento')
            ->with('json', $json)
              ->with('consult', $consult)
              ->with('id_actividad', $idActvidad);
	}

    public function DetallesArchivos($idarc){
        $idarc = decrypt($idarc);
        $query = DB::SELECT("SELECT res.idarseg, res.nombre, res.detalle_a, res.ruta
        FROM archivos_seguimientos AS res
        INNER JOIN seguimientos_actividades AS seg ON seg.idseac = res.idseac_seguimientos_actividades
        WHERE res.idseac_seguimientos_actividades = $idarc");
        return response()->json($query);
    }



    public function actividades(){

        $hoy = Carbon::now()->locale('es_MX')->format('d-m-Y');
        $consul = DB::table('actividades')->count() + 1;
        $tipous = DB::table('areas')->get()->all();
        $tipo_actividad = DB::table('tipos_actividades')
        ->orderBy('nombre','Asc')
        ->get();

        $user = DB::table('users')
                    ->join('tipos_usuarios', 'tipos_usuarios.idtu', '=' , 'users.idtu_tipos_usuarios')
                    ->join('areas', 'areas.idar', '=' , 'users.idar_areas')
                    ->select('users.idu',
                            'users.titulo',
                            'users.nombre',
                            'users.app',
                            'users.apm',
                            'tipos_usuarios.nombre as tipo_usuario',
                            'areas.nombre as nombre_areas',
                            'areas.idar',
                            )
                    ->where('users.idu' , '=', Auth()->user()->idu)
                    ->get();

        return view('Actividades.actividades')
        ->with('hoy', $hoy)
        ->with('consul', $consul)
        ->with('tipo_actividad', $tipo_actividad)
        ->with('tipous', $tipous)
        ->with('user', $user);
    }

    public function tipousuarios(Request $request){

        //$id_user = Auth()->user()->idu;
        $id = $request->tipo_u;
        $id_seleccionado;

        for($b=0; $b < count($id); $b++){

            $consul = DB::Select("SELECT  u.idu, u.titulo,u.nombre,u.app,u.apm, tu.nombre AS tipo_area, a.nombre AS areas  FROM users AS u
            INNER JOIN tipos_usuarios AS tu ON tu.idtu = u.idtu_tipos_usuarios
            INNER JOIN areas AS a ON a.idar = u.idar_areas
            WHERE u.idtu_tipos_usuarios NOT IN(1)
            AND a.idar = $id[$b]");

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

        if($r->file('archivos3') != null){

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
                    '$horadeinicio', '$fechatermino', '$horatermino', '$tipoactividad', '$idar_areas', '$idusuario', '$estado',
                    '$importancia', '$archivos', '$archivos2', '$archivos3', '$link', '$link2', '$link3')");


        $consul = DB::table('actividades')->max('idac');

        for($i=0; $i < count($tipousuarioarea); $i++){

            DB::INSERT("INSERT INTO responsables_actividades (idu_users , idac_actividades) VALUES ('$tipousuarioarea[$i]','$consul')");
              
            
            //---------------------------llenado de otras tablas---------------


              $idreac_responsables_actividades = DB::table('responsables_actividades')->max('idreac');
             
              DB::INSERT("INSERT INTO seguimientos_actividades (idreac_responsables_actividades , fecha , detalle,estado) 
              VALUES ('$idreac_responsables_actividades','$fechacreacion','sin detalles','pendiente')");


              $idseac_seguimientos_actividades = DB::table('seguimientos_actividades')->max('idseac');

              DB::INSERT("INSERT INTO archivos_seguimientos (idseac_seguimientos_actividades, nombre, ruta, detalle_a)
              VALUES ('$idseac_seguimientos_actividades','Sin archivo','Sin archivo','Sin archivo')");
                  //---------------------------fin del llenado----------------------
        }

          

        if (Auth()->User()->idtu_tipos_usuarios == 3) {
            return redirect()->route('reporte_actividades');
        }else{
            return redirect()->route('actividades_creadas',['id'=>encrypt(Auth()->User()->idu)]);
        }

    }

    public function actividades_modificacion($id){

        $id = decrypt($id);
        $consul = DB::table('actividades')->where('idac', $id)
        ->join('users', 'users.idu', '=', 'actividades.idu_users')
        ->join('areas', 'areas.idar', '=', 'actividades.idar_areas')
        ->join('tipos_usuarios', 'tipos_usuarios.idtu', '=' , 'users.idtu_tipos_usuarios')
        ->join('tipos_actividades', 'tipos_actividades.idtac', '=' , 'actividades.idtac_tipos_actividades')
        ->select(
            'actividades.idac',
            'tipos_actividades.nombre as nombre_actividad',
            'actividades.asunto',
            'actividades.idtac_tipos_actividades',
            'actividades.descripcion',
            'actividades.fecha_creacion',
            'actividades.turno',
            'actividades.comunicado',
            'actividades.fecha_inicio',
            'actividades.fecha_fin',
            'actividades.hora_inicio',
            'actividades.hora_fin',
            'areas.nombre as nombre_area',
            'tipos_usuarios.nombre as tipo_usuario',
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
        
        $personas = DB::SELECT("SELECT CONCAT(us.titulo, ' ' , us.nombre, ' ', us.app, ' ', us.apm) AS nombre, ar.nombre AS nombre_area
                                FROM responsables_actividades AS re
                                INNER JOIN actividades AS ac ON ac.idac = re.idac_actividades
                                INNER JOIN users AS us ON us.idu = re.idu_users
                                INNER JOIN areas AS ar ON ar.idar = us.idar_areas
                                WHERE re.acuse = 1
                                AND re.idac_actividades = $id
                                ORDER BY ar.nombre ASC");
        $array = array();

        foreach($personas as $personas){

            array_push($array, array(
                                    "personas" => $personas->nombre,
                                    "areas" => $personas->nombre_area,
            ));
        }

        $json = json_encode($array);


        $tipous = DB::SELECT("SELECT a.nombre, a.`idar`
        FROM actividades AS ac
        INNER JOIN responsables_actividades AS re ON re.idac_actividades = ac.idac
        INNER JOIN users AS u ON u.idu = re.idu_users
        INNER JOIN areas AS a ON a.idar = u.idar_areas
        WHERE ac.idac = $id
        GROUP BY a.nombre");
       
        
        $array2 = array();
        
        foreach($tipous as $t){
            array_push($array2, $t->idar,);
        }
        
        $no_seleccionar = DB::SELECT("SELECT *
        FROM areas AS ar
        WHERE ar.idar NOT IN (" . implode(',', $array2) . ")");

        
        
        
        //return $tipous;
        //return $no_seleccionar;

        $users = DB::SELECT("SELECT u.idu, CONCAT(u.titulo, ' ' , u.app, ' ', u.apm, ' ' , u.nombre) AS usuario,
        a.idar
        FROM actividades AS ac
        INNER JOIN responsables_actividades AS re ON re.idac_actividades = ac.idac
        INNER JOIN users AS u ON u.idu = re.idu_users
        INNER JOIN areas AS a ON a.idar = u.idar_areas
        WHERE ac.idac = $id");

        $array3 = array();
        $array4 = array();

        foreach($users as $us){
            array_push($array3, $us->idu);
            array_push($array4, $us->idar);
        }
        
        $no_seleccionar_user = DB::SELECT("SELECT us.idu, CONCAT(us.titulo, ' ' , us.app, ' ', us.apm, ' ' , us.nombre) AS usuario
        FROM users AS us
        INNER JOIN areas AS ar ON ar.idar = us.idar_areas
        WHERE us.idu NOT IN(" . implode(',', $array3) . ")
        AND ar.idar IN (" . implode(',', $array4) . ")");

        $tipo_actividad = DB::table('tipos_actividades')
        ->whereNotIn('idtac',[$consul[0]->idtac_tipos_actividades])
        ->orderBy('nombre','Asc')
        ->get();
        

        return view('Actividades.modificar_actividad')
        ->with('consul', $consul)
        ->with('tipo_actividad', $tipo_actividad)
        ->with('tipous', $tipous)
        ->with('users', $users)
        ->with('json', $json)
        ->with('no_seleccionar', $no_seleccionar)
        ->with('no_seleccionar_user', $no_seleccionar_user);
    }


    public function quitar_ajax(Request $request){

        $val = $request->val;
        $id = $request->id;

        $consul = DB::SELECT("SELECT * FROM responsables_actividades AS re
                        WHERE re.idu_users = $val
                        AND re.idac_actividades = $id");


        return response()->json($consul);
    }

    public function quitar_ajax2(Request $request){

        $val = $request->val;
        $id = $request->id;

        $consul = DB::SELECT("SELECT COUNT(re.acuse) AS contar FROM users AS us
        INNER JOIN responsables_actividades AS re ON re.idu_users = us.idu
        WHERE re.acuse = 1
        AND re.idac_actividades = $id
        AND us.idar_areas = $val");

        $consul2 = DB::SELECT("SELECT us.idu FROM users AS us
        INNER JOIN areas AS ar ON ar. idar = us.idar_areas
        WHERE ar.idar = $val");
        return response()->json([$consul, $consul2]);
    }

    public function update_actividades(Request $r){

        $id = $r->idac;
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


        if(\Storage::disk('local')->exists($r->archivosoculto)){

            $archivos = $r->archivosoculto;

        }elseif($r->file('archivos') != null){

            $file = $r->file('archivos');
            $archivos = $file->getClientOriginalName();
            $archivos = date('Ymd_His_') . $archivos;
            \Storage::disk('local')->put($archivos, \File::get($file));

        }else{

            $archivos = 'Sin archivo';
        }

        if(\Storage::disk('local')->exists($r->archivosoculto2)){

            $archivos2 = $r->archivosoculto2;

        }else if($r->file('archivos2') != null){

            $file2 = $r->file('archivos2');
            $archivos2 = $file2->getClientOriginalName();
            $archivos2 = date('Ymd_His_') . $archivos2;
            \Storage::disk('local')->put($archivos, \File::get($file2));

        }else{

            $archivos2 = 'Sin archivo';
        }

        if(\Storage::disk('local')->exists($r->archivosoculto3)){

            $archivos3 = $r->archivosoculto3;

        }else if($r->file('archivos3') != null){

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


        DB::UPDATE("UPDATE actividades SET asunto = '$Asunto', descripcion ='$detalleactividad', fecha_creacion = '$fechacreacion',
        turno = '$turno',  comunicado = '$comunicado', fecha_inicio = '$fechainicio',
        hora_inicio = '$horadeinicio', fecha_fin = '$fechatermino', hora_fin = '$horatermino', idtac_tipos_actividades = '$tipoactividad',
        status = '$estado',
        importancia = '$importancia',  archivo1 = '$archivos', archivo2 = '$archivos2', archivo3 = '$archivos3',
        link1 = '$link', link2 = '$link2', link3 = '$link3'
        WHERE idac = $id");

        

        for($i=0; $i < count($tipousuarioarea); $i++){
    
            $prueba = DB::SELECT("SELECT idu_users FROM responsables_actividades WHERE idac_actividades= $id AND idu_users = $tipousuarioarea[$i]");
            
            if(count($prueba) == 0){
                DB::INSERT("INSERT INTO responsables_actividades(idu_users, idac_actividades) VALUES ($tipousuarioarea[$i] , $id)");
            }
            
            
        }
        
        
	if (Auth()->User()->idtu_tipos_usuarios == 3) {
            return redirect()->route('reporte_actividades');
        }else{
            return redirect()->route('actividades_creadas',['id'=>encrypt(Auth()->User()->idu)]);
        }

    }

    public function activacion($id, $activo){

        $id = decrypt($id);
        $activo = decrypt($activo);

        if($activo == 1){

            DB::UPDATE("UPDATE actividades SET activo = '0' WHERE idac = $id");

        }else{
            DB::UPDATE("UPDATE actividades SET activo = '1' WHERE idac = $id");
        }
        
        return redirect()->route('actividades_creadas',['id'=>encrypt(Auth()->User()->idu)]);
    }

    public function actividades_creadas($id)
    {
        $id_u = decrypt($id);

        $ac_cre = DB::SELECT("SELECT  ac.idac ,ac.turno, ac.fecha_creacion, ac.asunto ,CONCAT(us.titulo, ' ', us.nombre, ' ', us.app, ' ', us.apm) AS creador,
        CONCAT(ac.fecha_inicio, ' al ', ac.fecha_fin) AS periodo, ac.importancia, ar.nombre, ac.activo, ra.acuse, ra.idu_users, ac.descripcion,
        porcentaje(ac.idac, $id_u) AS porcentaje
        FROM actividades AS ac
        INNER JOIN users AS us ON us.idu = ac.idu_users
        INNER JOIN areas AS ar ON ar.idar = ac.idar_areas
        LEFT JOIN responsables_actividades AS ra ON ra.idac_actividades = ac.idac
        LEFT JOIN seguimientos_actividades AS sa ON sa.idreac_responsables_actividades = idreac
        WHERE ac.idu_users = $id_u
        GROUP BY ac.idac
        ORDER BY ac.fecha_creacion DESC");

        $array = array();

        function recorrer($value){
            if (gettype($value) == "string") {
                $val = explode('*', $value);
                $arr = array('1'=> explode('-', $val[0]),'2'=>$val[1]);
            }else{
                $arr = null;
            }
            return $arr;
        }

        function btn($idac, $activo){


            if($activo == 1){
                return "<a target='_blank' class='btn btn-success btn-sm' onclick=window.open(this.href,this.target,width=600,height=800); href=".route('Detalles', ['id' => encrypt($idac)]) .">Detalle</a>
                <a class='btn btn-danger mt-1 btn-sm' href=".route('activacion',['id' => encrypt($idac), 'activo' => encrypt($activo)]).">Desactivar</a>
                <a class='btn btn-warning mt-1 btn-sm' href=".route('edit_modificacion', ['id' => encrypt($idac)]).">Modificar</a>";
            }else{
                return "<a target='_blank' class='btn btn-success btn-sm' onclick=window.open(this.href,this.target,width=600,height=800); href=".route('Detalles', ['id' => encrypt($idac)]) .">Detalle</a>
                <a class='btn btn-primary mt-1 btn-sm' href=".route('activacion',['id' => encrypt($idac), 'activo' => encrypt($activo)]).">Activar</a>
                <a class='btn btn-warning mt-1 btn-sm' href=".route('edit_modificacion', ['id' => encrypt($idac)]).">Modificar</a>";
            }
        }

        function AB($data){

            if(gettype($data) == "array"){

                return $data['1'][0]." de ".$data['1'][1];
            }else{
                return 0;
            }
        }

        function C($data){

            if(gettype($data) == "array"){

                return number_format($data['2'], 0, '.', ' ').'%';
            }else{
                return 0;
            }

        }



        foreach($ac_cre as $c){

            $data = recorrer($c->porcentaje);

            array_push($array, array('idac' => $c->idac,
                                    'turno' => $c->turno,
                                    'fecha_creacion' => $c->fecha_creacion,
                                    'asunto' => $c->asunto,
                                    'descripcion' => $c->descripcion,
                                    'creador' => $c->creador,
                                    'periodo' => $c->periodo,
                                    'importancia' => $c->importancia,
                                    'nombre' => $c->nombre,
                                    'activo' => $c->activo,
                                    'acuse' => $c->acuse,
                                    'idu_users' => $c->idu_users,
                                    'AB' => AB($data),
                                    'C' =>  C($data),
                                    'operaciones' => btn($c->idac, $c->activo),
                                    ));
        }



        $json = json_encode($array);
        
        return view ('Actividades.actividadescreadas', compact('json'));

    }


}
