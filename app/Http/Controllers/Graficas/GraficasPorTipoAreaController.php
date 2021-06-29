<?php

namespace App\Http\Controllers\Graficas;

use App\Http\Controllers\Controller;
use App\Models\ResponsablesActividades;
use App\Models\SeguimientosActividades;
use App\Models\TiposActividades;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraficasPorTipoAreaController extends Controller
{
    public function dashboard(User $user)
    {
        $tiposActividades = ResponsablesActividades::join('actividades','actividades.idac','responsables_actividades.idac_actividades')
            ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('responsables_actividades.idu_users',$user->idu)
            ->select('tipos_actividades.idtac', 'tipos_actividades.nombre')
            ->groupBy('tipos_actividades.nombre')
            ->get();
        return view('sistema.graficas.dashboard-por-tipo-area',[
            'tipo_actividades' => $tiposActividades
        ]);
    }

    public function getEstadisticasDeActividades(User $user, Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;
        $tiposActividades = TiposActividades::whereIn('idtac', $request->tipos_actividades)
            ->get()
            ->each(function($tipoActividad) use($user,$inicio,$fin){
                $tipoActividad->actividadesCompletadas = $this->getActividadesCompletadas($user,$tipoActividad,$inicio,$fin)->count();
                $tipoActividad->actividadesEnProceso = $this->getActividadesEnProceso($user,$tipoActividad,$inicio,$fin)->count();
                $tipoActividad->actividadesSinEntregar = $this->getActividadesSinEntregar($user,$tipoActividad,$inicio,$fin)->count();
                $tipoActividad->actividadesConAcuseDeRecibido = $this->getActividadesConAcuseDeRecibido($user,$tipoActividad,$inicio,$fin)->count();
                $tipoActividad->actividadesSinAcuseDeRecibido = $this->getActividadesSinAcuseDeRecibido($user,$tipoActividad,$inicio,$fin)->count();

                $tipoActividad->actividadesTotales = $tipoActividad->actividadesCompletadas +
                                                    $tipoActividad->actividadesEnProceso +
                                                    $tipoActividad->actividadesSinEntregar +
                                                    $tipoActividad->actividadesConAcuseDeRecibido +
                                                    $tipoActividad->actividadesSinAcuseDeRecibido;


                return $tipoActividad;
            });

        return $tiposActividades;
    }
    public function actividadesCompletadas(User $user, Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;
        $tiposActividades = TiposActividades::whereIn('idtac', $request->tipos_actividades)
            ->get()
            ->each(function($tipoActividad) use($user,$inicio,$fin){
                $tipoActividad->actividades = $this->getActividadesCompletadas($user,$tipoActividad,$inicio,$fin);
                return $tipoActividad;
            });
        return $tiposActividades;
    }

    public function actividadesEnProceso(User $user, Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;
        $tiposActividades = TiposActividades::whereIn('idtac', $request->tipos_actividades)
            ->get()
            ->each(function($tipoActividad) use($user,$inicio,$fin){
                $tipoActividad->actividades = $this->getActividadesEnProceso($user,$tipoActividad,$inicio,$fin);
                return $tipoActividad;
            });
        return $tiposActividades;
    }
    public function actividadesSinEntregar(User $user, Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;
        $tiposActividades = TiposActividades::whereIn('idtac', $request->tipos_actividades)
            ->get()
            ->each(function($tipoActividad) use($user,$inicio,$fin){
                $tipoActividad->actividades = $this->getActividadesSinEntregar($user,$tipoActividad,$inicio,$fin);
                return $tipoActividad;
            });
        return $tiposActividades;
    }
    public function actividadesConAcuseDeRecibido(User $user, Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;
        $tiposActividades = TiposActividades::whereIn('idtac', $request->tipos_actividades)
            ->get()
            ->each(function($tipoActividad) use($user,$inicio,$fin){
                $tipoActividad->actividades = $this->getActividadesConAcuseDeRecibido($user,$tipoActividad,$inicio,$fin);
                return $tipoActividad;
            });
        return $tiposActividades;
    }
    public function actividadesSinAcuseDeRecibido(User $user, Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;
        $tiposActividades = TiposActividades::whereIn('idtac', $request->tipos_actividades)
            ->get()
            ->each(function($tipoActividad) use($user,$inicio,$fin){
                $tipoActividad->actividades = $this->getActividadesSinAcuseDeRecibido($user,$tipoActividad,$inicio,$fin);
                return $tipoActividad;
            });
        return $tiposActividades;
    }


    public function getActividades(User $user, Request $request)
    {
        return  TiposActividades::whereIn('idtac', $request->tipos_actividades)
            ->get()
            ->each(function($tipoActividad) use($user,$request){
                $tipoActividad->actividades_completadas = $this->getActividadesCompletadas($user,$tipoActividad,$request->inicio,$request->fin);
                $tipoActividad->actividades_en_proceso = $this->getActividadesEnProceso($user,$tipoActividad,$request->inicio,$request->fin);
                $tipoActividad->actividades_sin_entregar = $this->getActividadesSinEntregar($user,$tipoActividad,$request->inicio,$request->fin);
                $tipoActividad->actividades_con_acuse_de_recibido = $this->getActividadesConAcuseDeRecibido($user,$tipoActividad,$request->inicio,$request->fin);
                $tipoActividad->actividades_sin_acuse_de_recibido = $this->getActividadesSinAcuseDeRecibido($user,$tipoActividad,$request->inicio,$request->fin);
                return $tipoActividad;
            });
    }

    public function getActividadesCompletadas(User $user, TiposActividades $tiposActividades, $inicio, $fin){
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return User::where('idu', $user->idu)
        ->join('responsables_actividades', 'idu_users', 'users.idu')
        ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
        ->join('areas','areas.idar','actividades.idar_areas')
        ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
        ->where('responsables_actividades.fecha','!=', null)
        ->where('actividades.idtac_tipos_actividades', $tiposActividades->idtac)
        ->where('actividades.fecha_fin','<=', $fin->format('Y-m-d'))
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

    public function getActividadesEnProceso(User $user, TiposActividades $tiposActividades, $inicio, $fin){
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return User::where('idu', $user->idu)
        ->join('responsables_actividades', 'idu_users', 'users.idu')
        ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
        ->join('areas','areas.idar','actividades.idar_areas')
        ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
        ->where('responsables_actividades.fecha', null)
        ->where('actividades.idtac_tipos_actividades', $tiposActividades->idtac)
        ->where('actividades.fecha_inicio','>=', $inicio->format('Y-m-d'))
        ->where('actividades.fecha_fin','<=', $fin->format('Y-m-d'))
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

    public function getActividadesSinEntregar(User $user, TiposActividades $tiposActividades, $inicio, $fin){
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return User::where('idu', $user->idu)
        ->join('responsables_actividades', 'idu_users', 'users.idu')
        ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
        ->join('areas','areas.idar','actividades.idar_areas')
        ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
        ->where('responsables_actividades.fecha', null)
        ->where('responsables_actividades.fecha','<', Carbon::now()->format('Y-m-d'))
        ->where('actividades.idtac_tipos_actividades', $tiposActividades->idtac)
        ->where('actividades.fecha_inicio','>=', $inicio->format('Y-m-d'))
        ->where('actividades.fecha_fin','<=', $fin->format('Y-m-d'))
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

    public function getActividadesConAcuseDeRecibido(User $user, TiposActividades $tiposActividades, $inicio, $fin){
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return User::where('idu', $user->idu)
        ->join('responsables_actividades', 'idu_users', 'users.idu')
        ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
        ->join('areas','areas.idar','actividades.idar_areas')
        ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
        ->where('responsables_actividades.firma','!=', null)
        ->where('actividades.idtac_tipos_actividades', $tiposActividades->idtac)
        ->where('actividades.fecha_inicio','>=', $inicio->format('Y-m-d'))
        ->where('actividades.fecha_fin','<=', $fin->format('Y-m-d'))
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

    public function getActividadesSinAcuseDeRecibido(User $user, TiposActividades $tiposActividades, $inicio, $fin){
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return User::where('idu', $user->idu)
        ->join('responsables_actividades', 'idu_users', 'users.idu')
        ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
        ->join('areas','areas.idar','actividades.idar_areas')
        ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
        ->where('responsables_actividades.firma', null)
        ->where('actividades.idtac_tipos_actividades', $tiposActividades->idtac)
        ->where('actividades.fecha_inicio','>=', $inicio->format('Y-m-d'))
        ->where('actividades.fecha_fin','<=', $fin->format('Y-m-d'))
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

    public function getActividadesPorTipoArea(User $user, Request $request){
        $inicio = $request->inicio;
        $fin = $request->fin;
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return [['actividades' => User::where('idu', $user->idu)
        ->join('responsables_actividades', 'idu_users', 'users.idu')
        ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
        ->join('areas','areas.idar','actividades.idar_areas')
        ->join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
        //->where('responsables_actividades.fecha', null)
        ->where('tipos_actividades.nombre', $request->tipo_area)
        ->where('actividades.fecha_inicio','>=', $inicio->format('Y-m-d'))
        ->where('actividades.fecha_fin','<=', $fin->format('Y-m-d'))
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

        })]];
    }


}
