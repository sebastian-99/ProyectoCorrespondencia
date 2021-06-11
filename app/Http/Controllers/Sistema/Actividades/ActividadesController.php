<?php

namespace App\Http\Controllers\Sistema\Actividades;

use App\Http\Controllers\Controller;
use App\Models\Actividades as actividades;
use App\Models\Areas as areas;
use App\Models\TiposAreas as tiposAreas;
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
        return $areas->actividades()
            ->where('actividades.fecha_fin','>=',"$inicio")
            ->where('actividades.fecha_fin','<=',"$fin")
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
            ->where('actividades.idar_areas', $areas->idar)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS nombre"),
                'actividades.asunto','actividades.fecha_inicio', 'actividades.fecha_fin',
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac'
            )
            ->get();
    }

    public function getActividadesCompletadas(areas $areas, $inicio, $fin , $year)
    {
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return actividades::join('responsables_actividades','idac_actividades', 'actividades.idac')
            ->join('users', 'users.idu','responsables_actividades.idu_users')
            ->where('actividades.idar_areas', $areas->idar)
            ->where('actividades.fecha_fin','>=',$inicio->format('Y-m-d'))
            ->where('actividades.fecha_fin','<=',$fin->format('Y-m-d'))
            ->where('responsables_actividades.fecha','!=',null)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS nombre"),
                'actividades.asunto','actividades.fecha_inicio', 'actividades.fecha_fin',
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac'
            )
            ->get();
    }
    public function getActividadesEnProceso(areas $areas, $inicio, $fin, $year)
    {
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return actividades::join('responsables_actividades','idac_actividades', 'actividades.idac')
            ->join('users', 'users.idu','responsables_actividades.idu_users')
            ->where('actividades.idar_areas', $areas->idar)
            ->where('actividades.fecha_fin','>=',$inicio->format('Y-m-d'))
            ->where('actividades.fecha_fin','<=',$fin->format('Y-m-d'))
            ->where('responsables_actividades.fecha',null)
            ->select(
                'users.idu',
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS nombre"),
                'actividades.asunto','actividades.fecha_inicio', 'actividades.fecha_fin',
                'actividades.importancia',
                'responsables_actividades.idreac',
                'actividades.idac'
            )
            ->get();
    }
    public function getActividadesSinEntregar(areas $areas, $inicio, $fin, $year)
    {
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return actividades::join('responsables_actividades','idac_actividades', 'actividades.idac')
            ->join('users', 'users.idu','responsables_actividades.idu_users')
            ->where('actividades.idar_areas', $areas->idar)
            ->where('actividades.fecha_fin','>=',$inicio->format('Y-m-d'))
            ->where('actividades.fecha_fin','<=',$fin->format('Y-m-d'))
            ->where('actividades.fecha_fin','<',Carbon::now()->format('Y-m-d'))
            ->where('responsables_actividades.fecha',null)
            ->select(
                DB::raw("CONCAT( users.titulo, '', users.nombre, ' ',users.app, ' ', users.apm) AS nombre"),
                'users.idu',
                'actividades.asunto','actividades.fecha_inicio', 'actividades.fecha_fin',
                'actividades.importancia','actividades.idac',
                'responsables_actividades.idreac'
            )
            ->get();
    }

    private function getfechasPorMes($mes,$year){
        $mes = new Carbon("$year/$mes/01");
        return [
            'inicio' => $mes->startOfMonth()->format('Y-m-d'),
            'fin' => $mes->endOfMonth()->format('Y-m-d')
        ];
    }

}
