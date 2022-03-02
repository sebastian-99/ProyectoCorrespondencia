<?php

namespace App\Http\Controllers\Sistema\TipoAreas;

use App\Http\Controllers\Controller;
use App\Models\Actividades;
use App\Models\Areas;
use App\Models\ResponsablesActividades;
use App\Models\SeguimientosActividades;
use App\Models\TiposActividades;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TipoAreasController extends Controller
{
    public function seguimiento($idac){
        return redirect()->route('Seguimiento',[ 'idac' => encrypt($idac) ]);
    }
    public function dashboard(User $user)
    {
        return view('sistema.tipo-actividades.dashboard',[
            'tipo_areas' => TiposActividades::select('idtac','nombre')->get()
        ]);
    }

    public function getActividadesṔorMes(User $user, TiposActividades $tiposActividades, $year, $mes)
    {
        $mes = new Carbon("$year/$mes/01");
        //return ['of'=>$of,'to'=>$to];
        $actividades = $this->getActividadesṔorRango(
            $user,
            $tiposActividades,
            $mes->startOfMonth()->format('Y-m-d'),
            $mes->endOfMonth()->format('Y-m-d')
        );
        $totalActividadesPorMes = $actividades->count();
        $actividades = $this->estadisticasDeActividades($actividades);
        if(!$actividades) return;

        $actividadesTotales = ResponsablesActividades::where('idu_users',$user->idu)
            ->count();

        $promedio = ($totalActividadesPorMes / $actividadesTotales)*100;

        return response()->json([
            'area' => $tiposActividades,
            'promedio' => number_format( $promedio, 2),
            'actividades' => $actividades,
        ]);
    }

    public function getActividadesṔorRangoDeFechas(User $user, TiposActividades $tiposActividades, $inicio, $fin)
    {
        $actividades = $this->getActividadesṔorRango($user, $tiposActividades, $inicio, $fin);
        $totalActividadesPorRango = $actividades->count();
        $actividades = $this->estadisticasDeActividades($actividades);

        if(!$actividades) return;
        $actividadesTotales = ResponsablesActividades::where('idu_users',$user->idu)
            ->count();

        $promedio = ($totalActividadesPorRango / $actividadesTotales)*100;

        return response()->json([
            'area' => $tiposActividades,
            'promedio' => number_format( $promedio, 2),
            'actividades' => $actividades,
        ]);
    }

    private function getActividadesṔorRango(User $user, TiposActividades $tiposActividades, $inicio, $fin)
    {
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return Actividades::join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->join('users','users.idu','actividades.idu_users')
            ->join('responsables_actividades','responsables_actividades.idac_actividades','actividades.idac')
            ->where('actividades.fecha_inicio','>=',$inicio->format('Y-m-d'))
            ->where('actividades.fecha_fin','<=',$fin->format('Y-m-d'))
            ->where('actividades.idtac_tipos_actividades',$tiposActividades->idtac)
            ->where('responsables_actividades.idu_users',$user->idu)
            ->get();
    }

    private function estadisticasDeActividades($actividades)
    {
        if( !is_array($actividades) && empty($actividades) ) return;
        $completadas = null;
        $enProceso = null;
        $incompletas = null;
        foreach($actividades AS $actividad){
            $actividad = Actividades::find($actividad->idac);
            $completadas += $actividad->total_completadas;
            $enProceso += $actividad->total_en_proceso;
            $incompletas += $actividad->total_incompletas;
        }

        return [
            'completadas' => $completadas,
            'enProceso' => $enProceso,
            'incompletas' => $incompletas,
        ];

    }
    public function getActividadesCompletadasPorMes(User $user, TiposActividades $tiposActividades, $mes, $year)
    {
        $mes = $this->getfechasPorMes($mes, $year);
        return $this->getActividadesCompletadas($user, $tiposActividades,$mes['inicio'],$mes['fin'],$year);
    }
    public function getActividadesEnProcesoPorMes(User $user,TiposActividades $tiposActividades, $mes, $year)
    {
        $mes = $this->getfechasPorMes($mes, $year);
        return $this->getActividadesEnProceso($user, $tiposActividades,$mes['inicio'],$mes['fin'],$year);
    }
    public function getActividadesSinEntregarPorMes(User $user, TiposActividades $tiposActividades, $mes, $year)
    {
        $mes = $this->getfechasPorMes($mes, $year);
        return $this->getActividadesSinEntregar($user, $tiposActividades,$mes['inicio'],$mes['fin'],$year);
    }

    public function getActividadesTotales(User $user, TiposActividades $tiposActividades)
    {
        return Actividades::join('responsables_actividades','idac_actividades', 'actividades.idac')
            ->join('users', 'users.idu','responsables_actividades.idu_users')
            ->join('areas', 'areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('actividades.idtac_tipos_actividades', $tiposActividades->idtac)
            ->where('users.idu', $user->idu)
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
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.idreac',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();
                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();
                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });
    }

    public function getActividadesCompletadas(User $user, TiposActividades $tiposActividades, $inicio, $fin , $year)
    {
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return Actividades::join('responsables_actividades','idac_actividades', 'actividades.idac')
            ->join('users', 'users.idu','responsables_actividades.idu_users')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->where('actividades.idtac_tipos_actividades', $tiposActividades->idtac)
            ->where('users.idu', $user->idu)
            ->where('actividades.fecha_fin','>=',$inicio->format('Y-m-d'))
            ->where('actividades.fecha_fin','<=',$fin->format('Y-m-d'))
            ->where('responsables_actividades.fecha','!=',null)
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
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.idreac',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();
                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();
                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });
    }
    public function getActividadesEnProceso(User $user, TiposActividades $tiposActividades, $inicio, $fin, $year)
    {
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return Actividades::join('responsables_actividades','idac_actividades', 'actividades.idac')
            ->join('users', 'users.idu','responsables_actividades.idu_users')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->where('actividades.idtac_tipos_actividades', $tiposActividades->idtac)
            ->where('users.idu', $user->idu)
            ->where('actividades.fecha_fin','>=',$inicio->format('Y-m-d'))
            ->where('actividades.fecha_fin','<=',$fin->format('Y-m-d'))
            ->where('responsables_actividades.fecha',null)
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
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.firma',
                'responsables_actividades.idreac',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();
                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();
                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });
    }
    public function getActividadesSinEntregar(User $user, TiposActividades $tiposActividades, $inicio, $fin, $year)
    {
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return Actividades::join('responsables_actividades','idac_actividades', 'actividades.idac')
            ->join('users', 'users.idu','responsables_actividades.idu_users')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->where('actividades.idtac_tipos_actividades', $tiposActividades->idtac)
            ->where('users.idu', $user->idu)
            ->where('actividades.fecha_fin','>=',$inicio->format('Y-m-d'))
            ->where('actividades.fecha_fin','<=',$fin->format('Y-m-d'))
            ->where('actividades.fecha_fin','<',Carbon::now()->format('Y-m-d'))
            ->where('responsables_actividades.fecha',null)
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
                'tipos_actividades.nombre AS tipo_actividad',
                'responsables_actividades.idreac',
                'responsables_actividades.firma'
            )
            ->get()
            ->each(function($collection){

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();
                $seguimiento = SeguimientosActividades::where('idreac_responsables_actividades',$collection->idreac)->get();
                $collection->numero_de_seguimiento = $seguimiento->count();
                $collection->porcentaje_seguimiento = $seguimiento->avg('porcentaje');

                $collection->seguimiento = $seguimiento->first();
                return $collection;

            });
    }

    private function getfechasPorMes($mes,$year){
        $mes = new Carbon("$year/$mes/01");
        return [
            'inicio' => $mes->startOfMonth()->format('Y-m-d'),
            'fin' => $mes->endOfMonth()->format('Y-m-d')
        ];
    }

}
