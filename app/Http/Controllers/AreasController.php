<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Models\TiposAreas;
use Illuminate\Http\Request;

class AreasController extends Controller
{
    /**
     * Vista para mostrar un listado de recursos.
     */
    public function index()
    {
        $areas = Areas::query()
                        ->join('tipos_areas', 'tipos_areas.idtar', '=' , 'areas.idtar')
                        ->select(
                                'areas.idar',
                                'areas.nombre',
                                'areas.activo',
                                'tipos_areas.nombre as idtar')
                        ->get();
        
        $tipos_areas = TiposAreas::all();
        $array = array();

        function btn($idar, $activo){
            if($activo == 1){
                $botones = "<a href=\"#eliminar\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('eliminar-area-$idar')\">Desactivar</a>"
                         . "<a href=\"#editar\" class=\"btn btn-primary mt-1\" data-toggle=\"modal\" data-target=\"#editarModal-$idar\">Editar</a>";
            } else {
                $botones = "<a href=\"#activar\" class=\"btn btn-info mt-1\" onclick=\"formSubmit('eliminar-area-$idar')\">Activar</a>";
            }

            return $botones;
        }

        #return btn(1, 1);
        foreach ($areas as $area){

            array_push($array, array(
                'idar'        =>$area->idar,
                'nombre'      =>$area->nombre,
                'idtar'       =>$area->idtar,
                'activo'      => ($area->activo == 1) ? "Si" : "No",
                'operaciones' => btn($area->idar, $area->activo)
            ));
        }

        $json = json_encode($array);

        return view('areas', compact('json','areas', 'tipos_areas'));
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
                    'nombre'  => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                                í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                                Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3', 'max:70'],
                    'idtar'   => ['required', 'integer', 'exists:tipos_areas,idtar'],
            ]);

        $guardar = Areas::query()
                        ->create([
                        'nombre' => $request->nombre,
                        'idtar'  => $request->idtar
            ]);

        return redirect()->route('areas.index')->with('mensaje', 'Se ha guardado correctamente');
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
                return view('areas', compact('areas'));
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
                return view('areas.edit', compact('areas'));
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
        if($idar) {
            $area = Areas::query()
                         ->where('idar', $idar)
                         ->first();
            if($area){
                $request->validate([
                    'nombre'  => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                                í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                                Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3', 'max:70'],
                    'idtar'   => ['required', 'integer', 'exists:tipos_areas,idtar'],
                    'activo' => ['required', 'boolean']
                ]);

                $actualizar = $area->update([
                    'nombre' => $request->nombre,
                    'idtar'  => $request->idtar,
                    'activo' => $request->activo
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
                $eliminar = $area->update([
                    'activo' => ($area->activo == 1) ? 0 : 1
                ]);
                return redirect()->route('areas.index')->with('mensaje', 'Su estado ha cambiado');
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }
}
