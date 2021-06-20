<?php

namespace App\Http\Controllers\Sistema\TipoAreas;

use App\Http\Controllers\Controller;
use App\Models\Actividades;
use App\Models\TiposActividades;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TipoAreasController extends Controller
{
    public function seguimiento($idac){
        return redirect()->route('detallesSeguimiento',[ 'idac' => encrypt($idac) ]);
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
        $actividades = $this->estadisticasDeActividades($actividades);
        if(!$actividades) return;
        return response()->json([
            'area' => $tiposActividades,
            'promedio' => $tiposActividades->promedio,
            'actividades' => $actividades,
        ]);
    }

    public function getActividadesṔorRangoDeFechas(User $user, TiposActividades $tiposActividades, $inicio, $fin)
    {
        $actividades = $this->getActividadesṔorRango($user, $tiposActividades, $inicio, $fin);

        $actividades = $this->estadisticasDeActividades($actividades);
        if(!$actividades) return;
        return response()->json([
            'area' => $tiposActividades,
            'promedio' => $tiposActividades->promedio,
            'actividades' => $actividades,
        ]);
    }

    private function getActividadesṔorRango(User $user, TiposActividades $tiposActividades, $inicio, $fin)
    {
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return Actividades::join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->join('users','users.idu','actividades.idu_users')
            ->where('users.idu', $user->idu)
            ->where('tipos_actividades.idtac', $tiposActividades->idtac )
            ->where('actividades.fecha_inicio','>=',$inicio->format('Y-m-d'))
            ->where('actividades.fecha_fin','<=',$fin->format('Y-m-d'))
            ->where('actividades.idtac_tipos_actividades',$tiposActividades->idac)
            ->get();
    }

    private function estadisticasDeActividades($tipoActividades)
    {
        if( !is_array($tipoActividades) && empty($tipoActividades) ) return;
        $completadas = null;
        $enProceso = null;
        $incompletas = null;
        foreach($tipoActividades AS $tipoActividad){
            $actividad = Actividades::find($tipoActividad->idtac_tipos_actividades);
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
                'areas.nombre AS area_responsable'
            )
            ->get()
            ->each(function($collection){

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

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
                'areas.nombre AS area_responsable'
            )
            ->get()
            ->each(function($collection){

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

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
                'areas.nombre AS area_responsable'
            )
            ->get()
            ->each(function($collection){

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

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
                'areas.nombre AS area_responsable'
            )
            ->get()
            ->each(function($collection){

                $collection->creador = User::where('idu',$collection->creador_id)
                    ->select('idu','titulo', 'nombre', 'app','apm')->first();

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
