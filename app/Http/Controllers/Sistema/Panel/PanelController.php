<?php

namespace App\Http\Controllers\Sistema\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function panel()
    {
        $user = auth()->user()->idu;
        $actividadesHoy = $this->actividadesHoy($user);
        $actividadesPendientes = $this->actividadesPendientes($user);
        $actividadesPorMes = $this->actividadesPorMes($user);
        $actividadesCerradas = $this->actividadesCerradas($user);

        return view('home',[
            'actividadesHoy' => $actividadesHoy,
            'actividadesPendientes' => $actividadesPendientes,
            'actividadesPorMes' => $actividadesPorMes,
            'actividadesCerradas' => $actividadesCerradas,
        ]);
    }

    private function actividadesHoy($idu){
        $hoy = Carbon::now()->format('Y-m-d');
        return User::where('idu', $idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->join('actividades', 'idac', 'responsables_actividades.idac_actividades')
            ->select('idu')
            ->where('actividades.fecha_fin', "$hoy")
            ->count();
    }

    private function actividadesPendientes($idu){
        return User::where('idu', $idu)
            ->join('responsables_actividades', 'idu_users', 'users.idu')
            ->select('idu')
            ->where('responsables_actividades.fecha', null)
            ->count();
    }

    private function actividadesPorMes($idu){
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
            ->count();
    }

    private function actividadesCerradas($idu){
        $usuario = User::find($idu);
        $total= $usuario->responsables()->count();
        $actividadesFaltantes = $usuario->responsables()->where('fecha', null)->count();

        return [
            'total' => $total,
            'faltantes' => $actividadesFaltantes,
            'concluidas' => $total-$actividadesFaltantes
        ];
    }


}
