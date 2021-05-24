<?php

namespace App\Http\Controllers\Sistema\Actividades;

use App\Http\Controllers\Controller;
use App\Models\areas;
use Illuminate\Http\Request;

class ActividadesController extends Controller
{
    public function dashboard()
    {
        return view('sistema.actividades.dashboard.dashboard');
    }

    public function getActividadesPorArea(areas $areas)
    {
        return response()->json([
            'areas' => $areas->actividades,
        ]);
    }
}
