<?php

namespace App\Http\Controllers\Sistema\Panel;

use App\Http\Controllers\Controller;
use App\Models\Actividades;
use App\Models\ResponsablesActividades as responsablesActividades;
use App\Models\SeguimientosActividades as seguimientosActividades;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{

    public function panel()
    {
        $user = auth()->user()->idu;
        $area = Auth()->user()->idar_areas;
        
        if (auth()->user()->idtu_tipos_usuarios == 4) {

            $user = DB::SELECT("SELECT idu, CONCAT(titulo, ' ',nombre, ' ', app, ' ', apm) AS nombre FROM users WHERE idtu_tipos_usuarios = 2 AND idar_areas = $area");
            $nombre = $user[0]->nombre;
            $user = $user[0]->idu;
            $actividadesEnSeguimiento = $this->getActividadesEnSeguimiento($user);
            return view('home',[
                'actividades_hoy' => $this->getActividadesHoy($user)->count(),
                'actividades_pendientes' => $this->getActividadesPendientes($user)->count(),
                'actividades_por_mes' => $this->getActividadesPorMes($user)->count(),
                'actividades_cerradas' => $this->getActividadesCerradas($user),
                'actividades_en_seguimiento' => [ 'completadas'=> $actividadesEnSeguimiento['completadas'], 'total' => $actividadesEnSeguimiento['total'] ],
                'actividades_completadas' => $this->getActividadesCompletadas()->count(),
                'actividades_en_proceso' => $this->getActividadesEnProceso()->count(),
                'actividades_sin_entregar' => $this->getActividadesSinEntregar()->count(),
                'actividades_con_acuse_de_recibido' => $this->getActividadesConAcuseDeRecibido()->count(),
                'actividades_sin_acuse_de_recibido' => $this->getActividadesSinAcuseDeRecibido()->count(),
                'user' => $user,
                'nombre' => $nombre
            ]);
        } else {
            $actividadesEnSeguimiento = $this->getActividadesEnSeguimiento($user);
            return view('home',[
                'actividades_hoy' => $this->getActividadesHoy($user)->count(),
                'actividades_pendientes' => $this->getActividadesPendientes($user)->count(),
                'actividades_por_mes' => $this->getActividadesPorMes($user)->count(),
                'actividades_cerradas' => $this->getActividadesCerradas($user),
                'actividades_en_seguimiento' => [ 'completadas'=> $actividadesEnSeguimiento['completadas'], 'total' => $actividadesEnSeguimiento['total'] ],
                'actividades_completadas' => $this->getActividadesCompletadas()->count(),
                'actividades_en_proceso' => $this->getActividadesEnProceso()->count(),
                'actividades_sin_entregar' => $this->getActividadesSinEntregar()->count(),
                'actividades_con_acuse_de_recibido' => $this->getActividadesConAcuseDeRecibido()->count(),
                'actividades_sin_acuse_de_recibido' => $this->getActividadesSinAcuseDeRecibido()->count(),
                'user' => $user,
            ]);
        }
    }

    public function getActividadesHoy($idu){
        $hoy = Carbon::now()->format('Y-m-d');
        return User::where('users.idu', $idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('actividades.aprobacion', 1)
            ->where('actividades.fecha_fin', "$hoy")
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                'actividades.fecha_inicio',
                'actividades.fecha_fin',
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $periodoInico = Carbon::parse($collection->fecha_inicio)->format('d-m-Y');
                $periodoFin = Carbon::parse($collection->fecha_fin)->format('d-m-Y');
                $collection->periodo = " $periodoInico al $periodoFin";

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();

                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });
    }

    public function getActividadesPendientes($idu){
        return User::where('users.idu', $idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('responsables_actividades.fecha', null)
            ->where('responsables_actividades.firma', null)
            ->where('actividades.aprobacion', 1)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                'actividades.fecha_inicio',
                'actividades.fecha_fin',
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $periodoInico = Carbon::parse($collection->fecha_inicio)->format('d-m-Y');
                $periodoFin = Carbon::parse($collection->fecha_fin)->format('d-m-Y');
                $collection->periodo = " $periodoInico al $periodoFin";

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();

                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });
    }

    public function getActividadesPorMes($idu){
        $hoy = Carbon::now();
        $mesInicial = $hoy->startOfMonth()->format('Y-m-d');
        $mesFinal = $hoy->endOfMonth()->format('Y-m-d');
        $actividadesFechaInicio = User::where('users.idu', $idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('actividades.fecha_inicio','>=', "$mesInicial")
            ->where('actividades.fecha_inicio','<=', "$mesFinal")
            ->where('responsables_actividades.fecha', null)
            ->where('responsables_actividades.firma',"!=", null)
            ->where('actividades.aprobacion', 1)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                'actividades.fecha_inicio',
                'actividades.fecha_fin',
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){
                $periodoInico = Carbon::parse($collection->fecha_inicio)->format('d-m-Y');
                $periodoFin = Carbon::parse($collection->fecha_fin)->format('d-m-Y');
                $collection->periodo = " $periodoInico al $periodoFin";

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();
                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });
            $actividadesFechaFin = User::where('users.idu', $idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('actividades.fecha_fin','>=', "$mesInicial")
            ->where('actividades.fecha_fin','<=', "$mesFinal")
            ->where('responsables_actividades.fecha', null)
            ->where('responsables_actividades.firma',"!=", null)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                'actividades.fecha_inicio',
                'actividades.fecha_fin',
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $periodoInico = Carbon::parse($collection->fecha_inicio)->format('d-m-Y');
                $periodoFin = Carbon::parse($collection->fecha_fin)->format('d-m-Y');
                $collection->periodo = " $periodoInico al $periodoFin";

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();
                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });

            $collection = collect([]);

            foreach ($actividadesFechaInicio As $actividad){
                $collection->push($actividad);
            }
            foreach ($actividadesFechaFin As $actividad){
                $collection->push($actividad);
            }

            return $collection->unique('idreac');
    }

    public function getActividadesCerradas($idu){
        $usuario = User::find($idu);
        $total= $usuario->responsables()->count();
        $actividades = ResponsablesActividades::join('seguimientos_actividades',
                'seguimientos_actividades.idreac_responsables_actividades',
                'responsables_actividades.idreac'
            )
            ->join('actividades','actividades.idac', 'responsables_actividades.idac_actividades')
            ->where('responsables_actividades.idu_users', $idu)
            ->where('seguimientos_actividades.porcentaje', 100)
            ->where('actividades.aprobacion', 1)
            ->groupBy('responsables_actividades.idreac')
            ->select('responsables_actividades.idreac')
            ->get();
        if(!request()->ajax()){
            return [
                'total' => $total,
                'concluidas' => $actividades->count()

            ];
        }
        return User::where('users.idu', $idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            //->where('responsables_actividades.fecha','!=', null)
            ->whereIn('responsables_actividades.idreac', $actividades)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                'actividades.fecha_inicio',
                'actividades.fecha_fin',
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $periodoInico = Carbon::parse($collection->fecha_inicio)->format('d-m-Y');
                $periodoFin = Carbon::parse($collection->fecha_fin)->format('d-m-Y');
                $collection->periodo = " $periodoInico al $periodoFin";

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();
                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });
    }

    public function getActividadesEnSeguimiento($idu)
    {
        $actividades = seguimientosActividades::join(
                'responsables_actividades',
                'responsables_actividades.idreac',
                'seguimientos_actividades.idreac_responsables_actividades'
            )
            ->join(
                    'actividades',
                    'actividades.idac',
                    'responsables_actividades.idac_actividades'
            )
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->join('users', 'users.idu','responsables_actividades.idu_users')
            ->where('responsables_actividades.idu_users', $idu)
            ->where('actividades.aprobacion', 1)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                'actividades.fecha_inicio',
                'actividades.fecha_fin',
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $periodoInico = Carbon::parse($collection->fecha_inicio)->format('d-m-Y');
                $periodoFin = Carbon::parse($collection->fecha_fin)->format('d-m-Y');
                $collection->periodo = " $periodoInico al $periodoFin";

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();
                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });

        if(request()->ajax()){
            return $actividades->where('porcentaje_seguimiento','100');
        }

        return [
            'actividades' => $actividades,
            'total' => $actividades->count(),
            'completadas' => $actividades->where('porcentaje_seguimiento','100')->count()
        ];

    }

    public function getActividadesCompletadas()
    {
        return User::where('idu', auth()->user()->idu)
        ->join('responsables_actividades', 'idu_users', 'users.idu')
        ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
        ->join('areas','areas.idar','actividades.idar_areas')
        ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
        ->where('responsables_actividades.fecha','!=', null)
        ->where('actividades.aprobacion', 1)
        ->select(
            'users.idu',
            DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
            'actividades.turno',
            'actividades.asunto',
            'actividades.descripcion',
            'actividades.created_at AS fecha_creacion',
            'actividades.fecha_inicio',
            'actividades.fecha_fin',
            'actividades.importancia',
            'responsables_actividades.idreac',
            'actividades.idac',
            'actividades.idu_users AS creador_id',
            'areas.nombre AS area_responsable',
            'tipos_actividades.nombre AS tipo_actividad',
            'responsables_actividades.firma'
        )
        ->get()
        ->each(function($collection){

            $periodoInico = Carbon::parse($collection->fecha_inicio)->format('d-m-Y');
            $periodoFin = Carbon::parse($collection->fecha_fin)->format('d-m-Y');
            $collection->periodo = " $periodoInico al $periodoFin";

            $collection->creador = User::where('idu',$collection->creador_id)
                ->select('idu','titulo', 'nombre', 'app','apm')->first();

            $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();
            $collection->numero_de_seguimiento = $seguimiento->count();
            $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

            $collection->seguimiento = $seguimiento->first();
            return $collection;

        });
    }
    public function getActividadesEnProceso()
    {
        return User::where('idu', auth()->user()->idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('responsables_actividades.fecha', null)
            ->where('actividades.aprobacion', 1)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                'actividades.fecha_inicio',
                'actividades.fecha_fin',
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $periodoInico = Carbon::parse($collection->fecha_inicio)->format('d-m-Y');
                $periodoFin = Carbon::parse($collection->fecha_fin)->format('d-m-Y');
                $collection->periodo = " $periodoInico al $periodoFin";

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();
                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });
    }
    public function getActividadesSinEntregar()
    {
        return User::where('idu', auth()->user()->idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('responsables_actividades.fecha', null)
            ->where('actividades.fecha_fin', '<', Carbon::now()->format('Y-m-d') )
            ->where('actividades.aprobacion', 1)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                'actividades.fecha_inicio',
                'actividades.fecha_fin',
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $periodoInico = Carbon::parse($collection->fecha_inicio)->format('d-m-Y');
                $periodoFin = Carbon::parse($collection->fecha_fin)->format('d-m-Y');
                $collection->periodo = " $periodoInico al $periodoFin";

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();
                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });
    }
    public function getActividadesConAcuseDeRecibido()
    {
        return User::where('idu', auth()->user()->idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('responsables_actividades.fecha', null)
            ->where('responsables_actividades.firma','!=', null)
            ->where('actividades.aprobacion', 1)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                'actividades.fecha_inicio',
                'actividades.fecha_fin',
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $periodoInico = Carbon::parse($collection->fecha_inicio)->format('d-m-Y');
                $periodoFin = Carbon::parse($collection->fecha_fin)->format('d-m-Y');
                $collection->periodo = " $periodoInico al $periodoFin";

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();
                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });

    }
    public function getActividadesSinAcuseDeRecibido()
    {
        return User::where('idu', auth()->user()->idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('responsables_actividades.fecha', null)
            ->where('responsables_actividades.firma', null)
            ->where('actividades.aprobacion', 1)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                'actividades.fecha_inicio',
                'actividades.fecha_fin',
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $periodoInico = Carbon::parse($collection->fecha_inicio)->format('d-m-Y');
                $periodoFin = Carbon::parse($collection->fecha_fin)->format('d-m-Y');
                $collection->periodo = " $periodoInico al $periodoFin";

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();
                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });
    }

}
