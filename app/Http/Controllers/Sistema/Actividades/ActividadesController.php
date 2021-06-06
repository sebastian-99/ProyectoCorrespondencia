<?php

namespace App\Http\Controllers\Sistema\Actividades;

use App\Http\Controllers\Controller;
use App\Models\actividades;
use App\Models\areas;
use App\Models\tiposAreas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActividadesController extends Controller
{
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

}
