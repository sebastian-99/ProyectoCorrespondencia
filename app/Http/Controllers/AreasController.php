<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Areas;

class AreasController extends Controller
{
    /**
     * Vista para mostrar un listado de recursos.
     */
    public function index()
    {
        $areas = Areas::all();
        return view('areas.index', compact('areas'));
    }

    /**
     * Vista que muestra un formulario para crear un recurso.
     */
    public function create()
    {
        return view('areas.create');
    }

    /**
     * Guardar un recurso.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'  => ['required', 'string', 'max:60'],
            'idtar'   => ['required', 'integer', 'exists:tipos_areas,idtar'],
            'estado'  => ['required', 'boolean']
        ]);
    }

    /**
     * Vista para mostrar un solo recurso.
     */
    public function show($idar)
    {
        if ($idar){
            $area = Areas::query()
                         ->where('idar', $idar)
                         ->first();
            if ($area) {
                return view('areas.show', compact('area'));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Vista que muestra un formulario para editar un recurso.
     */
    public function edit($idar)
    {
        if ($idar){
            $area = Areas::query()
                         ->where('idar', $idar)
                         ->first();
            if ($area){
                return view('areas.edit', compact('area'));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Actualiza un recurso.
     */
    public function update(Request $request, $idar)
    {
        if($idar){
            $area = Areas::query()
                         ->where('idar', $idar)
                         ->first();
            if($area){
                $request->validate([
                    'nombre'  => ['required', 'string', 'max:60'],
                    'idtar'   => ['required', 'integer', 'exists:tipos_areas,idtar'],
                    'estado'  => ['required', 'boolean']
                ]);

                $actualizar = $area->udpate([
                    'nombre' => $request->nombre,
                    'idtar'  => $request->idtar
                ]);

                return redirect()->route('areas.index')->with('mensaje', 'Se ha actualizado correctamente');
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }



    /**
     * Elimina un recurso.
     */
    public function destroy($idar)
    {
        if ($idar){
            $area = Areas::where('idar', $idar)
                         ->first();
            if ($area){
                $eliminar = $area->delete();
                return redirect()->route('areas.index')->with('mensaje', 'Se ha eliminado correctamente');
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }
}
