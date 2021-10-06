<?php
namespace App\Http\Controllers\Graficas;

use App\Http\Controllers\Controller;
use App\Models\Actividades;
use App\Models\ResponsablesActividades;
use App\Models\SeguimientosActividades;
use App\Models\TiposActividades;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use function App\Http\Controllers\por;

class GraficasDeActividadesCreadasController extends Controller
{
    public function detalleActividad($idac){
        return redirect()->route('Detalles',[ 'id' => encrypt($idac) ]);
    }
    public function seguimiento($idac){
        return redirect()->route('Seguimiento',[ 'idac' => encrypt($idac) ]);
    }
    public function dashboard(User $user)
    {
        $misActividades = Actividades::join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('actividades.idu_users',$user->idu)
            ->where('actividades.aprobacion', 1)
            ->groupBy('tipos_actividades.idtac')
            ->get();
        return view('sistema.graficas.actividades-creadas',[
            'tipo_actividades' => $misActividades,
            'user' => $user->idu,
        ]);
    }

    public function getEstadisticasDeActividades(User $user, Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;
        $tiposActividades = TiposActividades::whereIn('idtac', $request->tipos_actividades)
            ->get()
            ->each(function($tipoActividad) use($user,$inicio,$fin){
                $tipoActividad->actividadesCompletadas = $this->getActividadesCompletadas($user,$tipoActividad,$inicio,$fin,true);
                $tipoActividad->actividadesEnProceso = $this->getActividadesEnProceso($user,$tipoActividad,$inicio,$fin,true);
                $tipoActividad->actividadesSinEntregar = $this->getActividadesSinEntregar($user,$tipoActividad,$inicio,$fin,true);
                $tipoActividad->actividadesConAcuseDeRecibido = $this->getActividadesConAcuseDeRecibido($user,$tipoActividad,$inicio,$fin,true);
                $tipoActividad->actividadesSinAcuseDeRecibido = $this->getActividadesSinAcuseDeRecibido($user,$tipoActividad,$inicio,$fin,true);

                $tipoActividad->actividadesCompletadasEnTiempo = $this->getActividadesCompletadasEnTiempo($user,$tipoActividad,$inicio,$fin,true);
                $tipoActividad->actividadesCompletadasFueraDeTiempo = $this->getActividadesCompletadasFueraDeTiempo($user,$tipoActividad,$inicio,$fin,true);

                $tipoActividad->actividadesEnProcesoEnTiempo = $this->getActividadesEnProcesoEnTiempo($user,$tipoActividad,$inicio,$fin,true);
                $tipoActividad->actividadesEnProcesoFueraDeTiempo = $this->getActividadesEnProcesoFueraDeTiempo($user,$tipoActividad,$inicio,$fin,true);

                $tipoActividad->actividadesTotales = $tipoActividad->actividadesCompletadas +
                                                    $tipoActividad->actividadesEnProceso +
                                                    $tipoActividad->actividadesSinEntregar;


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

    public function actividadesCompletadasEnTiempo(User $user, Request $request){
        $inicio = $request->inicio;
        $fin = $request->fin;
        return TiposActividades::whereIn('idtac', $request->tipos_actividades)
            ->get()
            ->each(function($tipoActividad) use($user,$inicio,$fin){
                $tipoActividad->actividades = $this->getActividadesCompletadasEnTiempo($user,$tipoActividad,$inicio,$fin);
                return $tipoActividad;
            });
    }

    public function actividadesCompletadasFueraDeTiempo(User $user, Request $request){
        $tiposActividades = TiposActividades::whereIn('idtac', $request->tipos_actividades)
            ->get()
            ->each(function($tipoActividad) use($user,$request){
                $tipoActividad->actividades = $this->getActividadesCompletadasFueraDeTiempo($user,$tipoActividad,$request->inicio,$request->fin);
                return $tipoActividad;
            });
        return $tiposActividades;
    }

    public function actividadesEnProcesoEnTiempo(User $user,  Request $request){
        $inicio = $request->inicio;
        $fin = $request->fin;
        $tiposActividades = TiposActividades::whereIn('idtac', $request->tipos_actividades)
            ->get()
            ->each(function($tipoActividad) use($user,$inicio,$fin){
                $tipoActividad->actividades = $this->getActividadesEnProcesoEnTiempo($user,$tipoActividad,$inicio,$fin);
                return $tipoActividad;
            });
        return $tiposActividades;
    }

    public function actividadesEnProcesoFueraDeTiempo(User $user, Request $request){
        $inicio = $request->inicio;
        $fin = $request->fin;
        $tiposActividades = TiposActividades::whereIn('idtac', $request->tipos_actividades)
            ->get()
            ->each(function($tipoActividad) use($user,$inicio,$fin){
                $tipoActividad->actividades = $this->getActividadesEnProcesoFueraDeTiempo($user,$tipoActividad,$inicio,$fin);
                return $tipoActividad;
            });
        return $tiposActividades;
    }


    private function getActividades(User $user, TiposActividades $tiposActividades, $inicio, $fin)
    {
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        return Actividades::where('idu_users', $user->idu)
            ->where('actividades.idtac_tipos_actividades',$tiposActividades->idtac)
            ->where('actividades.fecha_inicio','>=',$inicio->format('Y-m-d'))
            ->where('actividades.fecha_fin','<=',$fin->format('Y-m-d'))
            ->where('actividades.aprobacion', 1)
            ->select('idac','fecha_fin')
            ->get()
            ->each(function($actividad){
                $responsables = ResponsablesActividades::where('idac_actividades',$actividad->idac)
                    ->select('idreac')
                    ->get()
                    ->each(function($responsable){
                        $idreac_responsables_actividades = $responsable->idreac;
                        $query = "SELECT fecha,ultimoporcentaje(idreac_responsables_actividades) AS ultimo_porcentaje
                            FROM seguimientos_actividades
                            WHERE idreac_responsables_actividades = $idreac_responsables_actividades";
                        $seguimiento = DB::select($query);

                        $responsable->porcentaje_seguimiento = count($seguimiento) > 0 ? $seguimiento[0]->ultimo_porcentaje : null;
                        $responsable->fecha_seguimiento = count($seguimiento) > 0 ? $seguimiento[0]->fecha : null;

                        return $responsable;
                    });
                $actividad->porcentaje = $responsables->avg('porcentaje_seguimiento');
                $actividad->responsables = $responsables;
                return $actividad;
            });

    }

    private function getActividadesFinales($actividades){
        return Actividades::join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
                ->join('areas','areas.idar','actividades.idar_areas')
                ->whereIn('actividades.idac', $actividades)
                ->where('actividades.aprobacion', 1)
                ->select(
                    'idac',
                    'areas.nombre',
                    'areas.nombre AS area_responsable',
                    'tipos_actividades.nombre AS tipo_actividad',
                    'actividades.comunicado',
                    'actividades.asunto',
                    'actividades.descripcion',
                    'actividades.created_at AS fecha_creacion',
                    'actividades.fecha_inicio',
                    'actividades.fecha_fin',
                    'actividades.importancia'
                )
                ->get()
                ->each(function($actividad){

                    $periodoInico = Carbon::parse($actividad->fecha_inicio)->format('d-m-Y');
                    $periodoFin = Carbon::parse($actividad->fecha_fin)->format('d-m-Y');
                    $actividad->periodo = " $periodoInico al $periodoFin";

                    $responsables = ResponsablesActividades::where('idac_actividades',$actividad->idac)
                        ->select('idreac','firma')
                        ->get()
                        ->each(function($responsable){
                            $idreac_responsables_actividades = $responsable->idreac;
                            $query = "SELECT fecha,ultimoporcentaje(idreac_responsables_actividades) AS ultimo_porcentaje
                                FROM seguimientos_actividades
                                WHERE idreac_responsables_actividades = $idreac_responsables_actividades";
                            $seguimiento = DB::select($query);

                            $responsable->porcentaje_seguimiento = count($seguimiento) > 0 ? $seguimiento[0]->ultimo_porcentaje : null;
                            $responsable->fecha_seguimiento = count($seguimiento) > 0 ? $seguimiento[0]->fecha : null;

                            return $responsable;
                        });
                    $actividad->porcentaje = $responsables->avg('porcentaje_seguimiento');
                    $actividadConAcuse = $responsables->where('firma','!=', null)->count();
                    $actividad->atendido_por = "$actividadConAcuse de ".$responsables->count();
                    return $actividad;
                });
    }

    private function getActividadesCompletadas(User $user, TiposActividades $tiposActividades, $inicio, $fin , $cantidad = false){
        $actividades = $this->getActividades($user,$tiposActividades,$inicio,$fin);
        $actividades_ids = [];
        foreach($actividades AS $actividad){
            if($actividad->porcentaje == 100)array_push($actividades_ids, $actividad->idac);
        }

        if($cantidad)return count($actividades_ids);

        if (count($actividades_ids) <= 0) return collect();
        return $this->getActividadesFinales($actividades_ids);

    }

    public function getActividadesEnProceso(User $user, TiposActividades $tiposActividades, $inicio, $fin, $cantidad = false){
        $actividades = $this->getActividades($user,$tiposActividades,$inicio,$fin);
        $actividades_ids = [];
        foreach($actividades AS $actividad){
            if($actividad->porcentaje < 100 && $actividad->porcentaje > 0 )array_push($actividades_ids, $actividad->idac);
        }

        if($cantidad)return count($actividades_ids);

        if (count($actividades_ids) <= 0) return collect();
        return $this->getActividadesFinales($actividades_ids);
    }

    public function getActividadesSinEntregar(User $user, TiposActividades $tiposActividades, $inicio, $fin, $cantidad = false){
        $actividades = $this->getActividades($user,$tiposActividades,$inicio,$fin);
        $actividades_ids = [];
        foreach($actividades AS $actividad){
            if($actividad->porcentaje <= 0 )array_push($actividades_ids, $actividad->idac);
        }

        if($cantidad)return count($actividades_ids);

        if (count($actividades_ids) <= 0) return collect();

        return $this->getActividadesFinales($actividades_ids);
    }

    public function getActividadesConAcuseDeRecibido(User $user, TiposActividades $tiposActividades, $inicio, $fin, $cantidad = false){
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        $actividades = Actividades::where('idu_users', $user->idu)
            ->where('actividades.idtac_tipos_actividades',$tiposActividades->idtac)
            ->where('actividades.fecha_inicio','>=',$inicio->format('Y-m-d'))
            ->where('actividades.fecha_fin','<=',$fin->format('Y-m-d'))
            ->where('actividades.aprobacion', 1)
            ->select('idac')
            ->get()
            ->each(function($actividad){
                $responsablesActividades = ResponsablesActividades::where('idac_actividades', $actividad->idac)->get();

                $actividadConAcuse = $responsablesActividades->where('firma', '!=', null)->count();
                $actividad->acuse = $actividadConAcuse == $responsablesActividades->count() ? true : false;
                return $actividad;
            });
        $actividades_ids = [];
        foreach($actividades AS $actividad){
            if($actividad->acuse)array_push($actividades_ids, $actividad->idac);
        }

        if($cantidad)return count($actividades_ids);

        if (count($actividades_ids) <= 0) return collect();

        return $this->getActividadesFinales($actividades_ids);
    }

    public function getActividadesSinAcuseDeRecibido(User $user, TiposActividades $tiposActividades, $inicio, $fin, $cantidad = false){
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        $actividades = Actividades::where('idu_users', $user->idu)
            ->where('actividades.idtac_tipos_actividades',$tiposActividades->idtac)
            ->where('actividades.fecha_inicio','>=',$inicio->format('Y-m-d'))
            ->where('actividades.fecha_fin','<=',$fin->format('Y-m-d'))
            ->where('actividades.aprobacion', 1)
            ->select('idac')
            ->get()
            ->each(function($actividad){
                $responsablesActividades = ResponsablesActividades::where('idac_actividades', $actividad->idac)->get();

                $actividadConAcuse = $responsablesActividades->where('firma', '!=', null)->count();
                $actividad->acuse = $actividadConAcuse < $responsablesActividades->count() ? true : false;
                return $actividad;
            });
        $actividades_ids = [];
        foreach($actividades AS $actividad){
            if($actividad->acuse)array_push($actividades_ids, $actividad->idac);
        }

        if($cantidad)return count($actividades_ids);

        if (count($actividades_ids) <= 0) return collect();

        return $this->getActividadesFinales($actividades_ids);
    }

    public function getActividadesPorTipoArea(User $user, Request $request){
        $inicio = $request->inicio;
        $fin = $request->fin;
        $inicio = new Carbon($inicio);
        $fin = new Carbon($fin);
        $actividades = Actividades::join('tipos_actividades','tipos_actividades.idtac','actividades.idtac_tipos_actividades')
            ->where('tipos_actividades.nombre', $request->tipo_area)
            ->where('actividades.fecha_inicio','>=', $inicio->format('Y-m-d)'))
            ->where('actividades.fecha_fin','<=', $fin->format('Y-m-d)'))
            ->where('actividades.idu_users', $user->idu)
            ->where('actividades.aprobacion', 1)
            ->select('actividades.idac')
            ->get();
        return [['actividades' =>$this->getActividadesFinales($actividades)]];
    }

    private function getActividadesCompletadasEnTiempo(User $user, TiposActividades $tiposActividades, $inicio, $fin, $cantidad = false){
        $actividades = $this->getActividades($user,$tiposActividades,$inicio,$fin);
        $actividades_ids = [];
        foreach($actividades AS $actividad){
            $fechaFin = new Carbon($actividad->fecha_fin);
            if($actividad->porcentaje == 100){
                foreach ($actividad->responsables AS $responsable){
                    $fecha = new Carbon($responsable->fecha_seguimiento);
                    $fecha->format('Y-m-d');
                    if(!($fecha > $fechaFin)){
                        if($actividad->porcentaje == 100)array_push($actividades_ids, $actividad->idac);
                    }
                }
            }
        }

        if($cantidad)return count($actividades_ids);

        if (count($actividades_ids) <= 0) return collect();
        return $this->getActividadesFinales($actividades_ids);
    }

    public function getActividadesCompletadasFueraDeTiempo(User $user, TiposActividades $tiposActividades, $inicio, $fin, $cantidad = false){
        $actividades = $this->getActividades($user,$tiposActividades,$inicio,$fin);
        $actividades_ids = [];
        foreach($actividades AS $actividad){
            $fechaFin = new Carbon($actividad->fecha_fin);
            if($actividad->porcentaje == 100){
                foreach ($actividad->responsables AS $responsable){
                    $fecha = new Carbon($responsable->fecha_seguimiento);
                    $fecha->format('Y-m-d');
                    if($fecha > $fechaFin){
                        if($actividad->porcentaje == 100)array_push($actividades_ids, $actividad->idac);
                    }
                }
            }
        }

        if($cantidad)return count($actividades_ids);

        if (count($actividades_ids) <= 0) return collect();
        return $this->getActividadesFinales($actividades_ids);
    }

    public function getActividadesEnProcesoEnTiempo(User $user, TiposActividades $tiposActividades, $inicio, $fin, $cantidad = false){
        $actividades = $this->getActividades($user,$tiposActividades,$inicio,$fin);
        $actividades_ids = [];
        foreach($actividades AS $actividad){
            $fechaFin = new Carbon($actividad->fecha_fin);
            if($actividad->porcentaje < 100 && $actividad->porcentaje > 0){
                $enProcesoEnTiempo = false;

                foreach ($actividad->responsables AS $responsable){

                    $fecha = new Carbon($responsable->fecha_seguimiento);
                    $fecha->format('Y-m-d');

                    if($actividad->porcentaje < 100 && $actividad->porcentaje > 0){
                        $hoy = Carbon::now()->format('Y-m-d');
                        if( ($hoy > $fecha  && $hoy > $fechaFin) && $responsable->porcentaje_seguimiento < 100 ){
                                $enProcesoEnTiempo = false;
                                break;
                            }
                        else{
                            if($fecha > $fechaFin){
                                $enProcesoEnTiempo = false;
                                break;
                            }
                            else{
                            $enProcesoEnTiempo = true;
                            }
                        }

                    }
                }
                if($enProcesoEnTiempo){
                    array_push($actividades_ids, $actividad->idac);
                }
            }
        }

        if($cantidad)return count($actividades_ids);

        if (count($actividades_ids) <= 0) return collect();
        return $this->getActividadesFinales($actividades_ids);
    }

    public function getActividadesEnProcesoFueraDeTiempo(User $user, TiposActividades $tiposActividades, $inicio, $fin, $cantidad = false){
        $actividades = $this->getActividades($user,$tiposActividades,$inicio,$fin);
        $actividades_ids = [];
        foreach($actividades AS $actividad){
            $fechaFin = new Carbon($actividad->fecha_fin);
            if($actividad->porcentaje < 100 && $actividad->porcentaje > 0){
                $enProcesoEnTiempo = false;

                foreach ($actividad->responsables AS $responsable){

                    $fecha = new Carbon($responsable->fecha_seguimiento);
                    $fecha->format('Y-m-d');

                    if($actividad->porcentaje < 100 && $actividad->porcentaje > 0){
                        $hoy = Carbon::now()->format('Y-m-d');
                        if( ($hoy > $fecha  && $hoy > $fechaFin) && $responsable->porcentaje_seguimiento < 100 ){
                                $enProcesoEnTiempo = false;
                                break;
                            }
                        else{
                            if($fecha > $fechaFin){
                                $enProcesoEnTiempo = false;
                                break;
                            }
                            else{
                            $enProcesoEnTiempo = true;
                            }
                        }

                    }
                }
                if(!$enProcesoEnTiempo){
                    array_push($actividades_ids, $actividad->idac);
                }
            }
        }

        if($cantidad)return count($actividades_ids);

        if (count($actividades_ids) <= 0) return collect();
        return $this->getActividadesFinales($actividades_ids);
    }

}
