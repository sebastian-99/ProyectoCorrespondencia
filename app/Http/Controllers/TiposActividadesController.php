<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TiposActividades;

class TiposActividadesController extends Controller
{
    /**
     * Vista para mostrar un listado de recursos
     */
    public function index()
    {
        $tipos_actividades = TiposActividades::all();
        return view('tipos-actividades.index', compact('tipos_actividades'));
    }

    /**
     * Vista que muestra un formulario para crear un recurso
     */
    public function create()
    {
        return view('tipos-actividades.create');
    }

    /**
     * Guarda un recurso
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:60']
        ]);

        $guardar = TiposActividades::query()
                                   ->create([
                                       'nombre' => $request->nombre
                                   ]);

        return redirect()->route('tipos-actividades.index')->with('mensaje', 'Se ha guardado correctamente');
    }

    /**
     * Vista para mostrar un solo recurso
     */
    public function show($idtac)
    {
        if ($idtac){
            $tipo_actividad = TiposActividades::query()
                                              ->where('idtac', $idtac)
                                              ->first();
            if($tipo_actividad){
                return view('tipos-actividades.show', compact('tipo_actividad'));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Vista que muestra un formulario para editar un recurso
     */
    public function edit($idtac)
    {
        if ($idtac){
            $tipo_actividad = TiposActividades::query()
                                              ->where('idtac', $idtac)
                                              ->first();
            if ($tipo_actividad) {
                return view('tipos-actividades.edit', compact('tipo_actividad'));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Actualiza un recurso
     */
    public function update(Request $request)
    {
        $request->validate([
            'idtac' => ['required', 'numeric', 'exists:tipos_actividades,idtac'],
            'nombre' => ['required', 'string', 'max:60']
        ]);

        $actualizar = TiposActividades::where('idtac', $request->idtac)
                                      ->udpate([
                                          'nombre' => $request->nombre
                                      ]);

        return redirect()->route('tipos-actividades.index')->with('mensaje', 'Se ha actualizado correctamente');
    }

    /**
     * Elimina un recurso
     */
    public function destroy($idtac)
    {
        if ($idtac){
            $tipo_actividad = TiposActividades::where('idtac', $idtac)
                                              ->first();
            if ($tipo_actividad){
                $eliminar = $tipo_actividad->delete();
                return redirect()->route('tipos-actividades.index')->with('mensaje', 'Se ha actualizado correctamente');
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }
}
