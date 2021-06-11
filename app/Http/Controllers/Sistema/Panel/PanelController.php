<?php

namespace App\Http\Controllers\Sistema\Panel;

use App\Http\Controllers\Controller;
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
            ->select('idu')
            ->where('actividades.fecha_fin', "$hoy")
            ->get();
    }

    public function getActividadesPendientes($idu){
        return User::where('idu', $idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->select('idu')
            ->where('responsables_actividades.fecha', null)
            ->get();
    }

    public function getActividadesPorMes($idu){
        $hoy = Carbon::now();
        $mesInicial = $hoy->startOfMonth()->format('Y-m-d');
        $mesFinal = $hoy->endOfMonth()->format('Y-m-d');
        return User::where('idu', $idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->where('actividades.fecha_fin','>=', "$mesInicial")
            ->where('actividades.fecha_fin','<=', "$mesFinal")
            ->where('responsables_actividades.fecha', null)
            ->select('idu','actividades.fecha_fin')
            ->get();
    }

    public function getActividadesCerradas($idu){
        $usuario = User::find($idu);
        $total= $usuario->responsables()->count();
        $actividadesConcluidas = $usuario->responsables()->where('fecha','!=', null)->get();

        return [
            'total' => $total,
            'concluidas' => $actividadesConcluidas->count()

        ];
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
            ->where('responsables_actividades.idu_users', $idu)
            ->select(
                'actividades.*',
                'seguimientos_actividades.porcentaje AS porcentaje_seguimiento'
            )
            ->get();
        return [
            'actividades' => $actividades,
            'total' => $actividades->count(),
            'completadas' => $actividades->where('porcentaje','100')->count()
        ];

    }


}
