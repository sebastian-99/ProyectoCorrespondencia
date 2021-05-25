<?php

namespace App\Http\Controllers;

use App\Models\seguimientosActividades;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    public function Seguimiento($idac)
    {
        $consulta = seguimientosActividades::join("responsables_actividades", "responsables_actividades.idreac", "=", "seguimientos_actividades.idreac")
            ->select(
                'seguimientos_actividades.*',
                'responsables_actividades.*'
            )
            ->get();

            return view('SeguimientoActividades/Seguimiento', compact('consulta'));
           
            
    }
}
