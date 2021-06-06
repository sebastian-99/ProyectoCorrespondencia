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
        $areas = Areas::all();
        $tipos_areas = TiposAreas::all();
        return view('areas', compact('areas', 'tipos_areas'));
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
                    'idtar'   => ['required', 'integer', 'exists:tipos_areas,idtar']
                ]);

                $actualizar = $area->update([
                    'nombre' => $request->nombre,
                    'idtar'  => $request->idtar,
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
