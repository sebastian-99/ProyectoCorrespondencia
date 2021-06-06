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
        return view('tipos-actividades', compact('tipos_actividades'));
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
        $this->validate($request,[
            'nombre' => ['required', 'string', 'max:60']
        ]);

        $guardar = TiposActividades::query()
                                    ->create([
                'nombre' => $request->nombre
            ]);

        return redirect()->route('tipos-actividades.index')->with('success', 'Se ha guardado correctamente');
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
                return view('tipos-actividades', compact('tipo_actividad'));
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
    public function update(Request $request, $idtac)
    {
        if ($idtac) {
            $tipo_actividad = TiposActividades::query()
                                            ->where('idtac', $idtac)
                                            ->first();
            if ($tipo_actividad) {
                $request->validate([
                    'nombre' => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                                í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                                Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3', 'max:70'],
                    'estado' => ['required', 'boolean']
                ]);

                $actualizar = $tipo_actividad->update([
                    'nombre' => $request->nombre,
                    'activo' => $request->activo
                ]);

                return redirect()->route('tipos-actividades.index')->with('mensaje', 'Se ha actualizado correctamente');

            } else {
                abort(404);
                }
            } else {
                abort(404);
        }
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
                return redirect()->route('tipos-actividades.index')->with('mensaje', 'Se ha eliminado correctamente');
          } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }
}
