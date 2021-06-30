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
        $tipos_actividades = TiposActividades::query()
                                            ->orderby('nombre', 'ASC')
                                             ->get();
        $array = array();

        function btn($idtac, $activo){
            if($activo == 1){
                $botones = "<a href=\"#eliminar\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('eliminar-tipos-actividades-$idtac')\"><i class='fas fa-power-off'></i></a>"
                         ."<a href=" . route('tipos-actividades.edit', $idtac) . " class=\"btn btn-primary mt-1\"> <i class='fas fa-edit'></i> </a>";
            } else {
                $botones = "<a href=\"#activar\" class=\"btn btn-info mt-1\" onclick=\"formSubmit('eliminar-tipos-actividades-$idtac')\"><i class='fas fa-lightbulb'></i></a>";
            }

            return $botones;
        }

        #return btn(1, 1);
        foreach ($tipos_actividades as $tipoactividades) {

            array_push($array, array(
                'idtac'       => $tipoactividades->idtac,
                'nombre'      => $tipoactividades->nombre,
                'activo'      => ($tipoactividades->activo == 1) ? "Si" : "No",
                'operaciones' => btn($tipoactividades->idtac, $tipoactividades->activo)
            ));
        }

        $json = json_encode($array);

        return view('tipos-actividades.index', compact('json', 'tipos_actividades'));
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
            'nombre' => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                                í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                                Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/"]
        ]);

        $guardar = TiposActividades::query()
                                    ->create([
                'nombre' => $request->nombre
            ]);

        return redirect()->route('tipos-actividades.index')->with('mensaje', 'El tipo de actividad se ha creado exitosamente');
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
            $tipos_actividades = TiposActividades::query()
                                              ->where('idtac', $idtac)
                                              ->get();
                return view('tipos-actividades.edit', compact('tipos_actividades'));
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
                    'nombre' => ['nullable', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                                í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                                Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/"],
                    'activo' => ['nullable', 'boolean']
                ]);

                $actualizar = $tipo_actividad->update([
                    'nombre' => $request->nombre,
                    'activo' => $request->activo
                ]);
                return redirect()->route('tipos-actividades.index')->with('mensaje', 'El tipo de actividad se ha actualizado exitosamente');

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
            $tipo_actividad = TiposActividades::query()
                                              ->where('idtac', $idtac)
                                              ->first();
            if ($tipo_actividad){
                $eliminar = $tipo_actividad->update([
                    'activo' => ($tipo_actividad->activo == 1) ? 0 : 1
                ]);
                return redirect()->route('tipos-actividades.index')->with('mensaje', 'Su estado ha cambiado');
          } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }
}
