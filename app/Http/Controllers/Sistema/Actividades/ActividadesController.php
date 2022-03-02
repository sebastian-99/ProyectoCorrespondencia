<?php

namespace App\Http\Controllers\Sistema\Actividades;

use App\Http\Controllers\Controller;
use App\Models\Actividades as actividades;
use App\Models\Areas as areas;
use App\Models\SeguimientosActividades;
use App\Models\TiposAreas as tiposAreas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActividadesController extends Controller
{

    public function seguimiento($idac){
        return redirect()->route('detallesSeguimiento',[ 'idac' => encrypt($idac) ]);
    }

    public function dashboard()
    {
        return view('sistema.actividades.dashboard.dashboard',[
            'areas' => tiposAreas::select('idtar','nombre')->get(),
        ]);
    }


    public function getAreasPorTipoArea(tiposAreas $tipoArea)
    {
        return response()->json([
            'area' => $tipoArea->areas,
        ]);
    }

    public function getActividadesPorArea(areas $areas)
    {
        return response()->json([
            'areas' => $areas->actividades,
        ]);
    }

    public function getActividadesṔorMes(areas $areas, $year, $mes)
    {
        $mes = new Carbon("$year/$mes/01");
        //return ['of'=>$of,'to'=>$to];
        $actividades = $this->getActividadesṔorRango(
            $areas,
            $mes->startOfMonth()->format('Y-m-d'),
            $mes->endOfMonth()->format('Y-m-d')
        );
        $actividades = $this->estadisticasDeActividades($actividades);
        if(!$actividades) return;
        return response()->json([
            'area' => $areas,
            'promedio' => $areas->promedio,
            'actividades' => $actividades,
        ]);
    }

    public function getActividadesṔorRangoDeFechas(areas $areas, $inicio, $fin)
    {
        $actividades = $this->getActividadesṔorRango($areas, $inicio, $fin);

        $actividades = $this->estadisticasDeActividades($actividades);
        if(!$actividades) return;
        return response()->json([
            'area' => $areas,
            'promedio' => $areas->promedio,
            'actividades' => $actividades,
        ]);
    }

    private function getActividadesṔorRango(areas $areas, $inicio, $fin)
    {
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return actividades::join('areas','areas.idar','actividades.idar_areas')
            ->where('actividades.fecha_inicio','>=',$inicio->format('Y-m-d'))
            ->where('actividades.fecha_fin','<=',$fin->format('Y-m-d'))
            ->where('areas.idar',$areas->idar)
            ->get();
    }

    private function estadisticasDeActividades($actividades)
    {
        if( !is_array($actividades) && empty($actividades) ) return;
        $completadas = null;
        $enProceso = null;
        $incompletas = null;
        foreach($actividades AS $actividad){
            $actividad = actividades::find($actividad->idac);
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
    public function getActividadesCompletadasPorMes(areas $areas, $mes, $year)
    {
        $mes = $this->getfechasPorMes($mes, $year);
        return $this->getActividadesCompletadas($areas,$mes['inicio'],$mes['fin'],$year);
    }
    public function getActividadesEnProcesoPorMes(areas $areas, $mes, $year)
    {

        $mes = $this->getfechasPorMes($mes, $year);
        return $this->getActividadesEnProceso($areas,$mes['inicio'],$mes['fin'],$year);
    }
    public function getActividadesSinEntregarPorMes(areas $areas, $mes, $year)
    {
        $mes = $this->getfechasPorMes($mes, $year);
        return $this->getActividadesSinEntregar($areas,$mes['inicio'],$mes['fin'],$year);
    }

    public function getActividadesTotales(areas $areas)
    {
        return actividades::join('responsables_actividades','idac_actividades', 'actividades.idac')
            ->join('users', 'users.idu','responsables_actividades.idu_users')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('actividades.idar_areas', $areas->idar)
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

    public function getActividadesCompletadas(areas $areas, $inicio, $fin , $year)
    {
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return actividades::join('responsables_actividades','idac_actividades', 'actividades.idac')
            ->join('users', 'users.idu','responsables_actividades.idu_users')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('actividades.idar_areas', $areas->idar)
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
    public function getActividadesEnProceso(areas $areas, $inicio, $fin, $year)
    {
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return actividades::join('responsables_actividades','idac_actividades', 'actividades.idac')
            ->join('users', 'users.idu','responsables_actividades.idu_users')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('actividades.idar_areas', $areas->idar)
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
    public function getActividadesSinEntregar(areas $areas, $inicio, $fin, $year)
    {
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return actividades::join('responsables_actividades','idac_actividades', 'actividades.idac')
            ->join('users', 'users.idu','responsables_actividades.idu_users')
            ->join('areas','areas.idar','actividades.idar_areas')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('actividades.idar_areas', $areas->idar)
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
