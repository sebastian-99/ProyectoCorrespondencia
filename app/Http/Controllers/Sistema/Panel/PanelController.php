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
        $actividadesEnSeguimiento = $this->getActividadesEnSeguimiento($user);

        return view('home',[
            'actividades_hoy' => $this->getActividadesHoy($user)->count(),
            'actividades_pendientes' => $this->getActividadesPendientes($user)->count(),
            'actividades_por_mes' => $this->getActividadesPorMes($user)->count(),
            'actividades_cerradas' => $this->getActividadesCerradas($user),
            'actividades_en_seguimiento' => [ 'completadas'=> $actividadesEnSeguimiento['completadas'], 'total' => $actividadesEnSeguimiento['total'] ]
        ]);
    }

    public function getActividadesHoy($idu){
        $hoy = Carbon::now()->format('Y-m-d');
        return User::where('idu', $idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->where('actividades.fecha_fin', "$hoy")
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                DB::raw("CONCAT(actividades.fecha_inicio, ' al ', actividades.fecha_fin) AS periodo"),
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                return $collection;

            });
    }

    public function getActividadesPendientes($idu){
        return User::where('idu', $idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->where('responsables_actividades.fecha', null)
            ->where('responsables_actividades.firma', null)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                DB::raw("CONCAT(actividades.fecha_inicio, ' al ', actividades.fecha_fin) AS periodo"),
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                return $collection;

            });
    }

    public function getActividadesPorMes($idu){
        $hoy = Carbon::now();
        $mesInicial = $hoy->startOfMonth()->format('Y-m-d');
        $mesFinal = $hoy->endOfMonth()->format('Y-m-d');
        return User::where('idu', $idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->where('actividades.fecha_fin','>=', "$mesInicial")
            ->where('actividades.fecha_fin','<=', "$mesFinal")
            ->where('responsables_actividades.fecha', null)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                DB::raw("CONCAT(actividades.fecha_inicio, ' al ', actividades.fecha_fin) AS periodo"),
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                return $collection;

            });
    }

    public function getActividadesCerradas($idu){
        $usuario = User::find($idu);
        $total= $usuario->responsables()->count();
        $actividadesConcluidas = $usuario->responsables()->where('fecha','!=', null)->get();
        if(!request()->ajax()){
            return [
                'total' => $total,
                'concluidas' => $actividadesConcluidas->count()

            ];
        }
        return User::where('idu', $idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->where('responsables_actividades.fecha','!=', null)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                DB::raw("CONCAT(actividades.fecha_inicio, ' al ', actividades.fecha_fin) AS periodo"),
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

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
            ->join('users', 'users.idu','responsables_actividades.idu_users')
            ->where('responsables_actividades.idu_users', $idu)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS responsable"),
                'actividades.turno',
                'actividades.asunto',
                'actividades.descripcion',
                'actividades.created_at AS fecha_creacion',
                DB::raw("CONCAT(actividades.fecha_inicio, ' al ', actividades.fecha_fin) AS periodo"),
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac',
                'actividades.idu_users AS creador_id',
                'areas.nombre AS area_responsable',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

                return $collection;

            });

        if(request()->ajax()){
            return $actividades;
        }

        return [
            'actividades' => $actividades,
            'total' => $actividades->count(),
            'completadas' => $actividades->where('porcentaje','100')->count()
        ];

    }


}
