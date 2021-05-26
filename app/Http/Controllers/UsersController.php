<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Vista para mostrar un listado de recuersos.
     */
    public function index()
    {
        $usuario = User::all();
        return view('users.index', compact('usuario'));
    }

    /**
     * Vista que muestra un formulario para crear un recurso.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Guardar un recurso.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idtu'   => ['required', 'integer', 'exists:tipos_usuarios,idtu'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5000'],
            'titulo' => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                        í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                        Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                        Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3', 'max:70'],
            'nombre' => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                        í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                        Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                        Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3', 'max:70'],
            'app'    => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                        í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                        Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                        Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3', 'max:70'],
            'apm'   => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                        í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                        Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                        Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3', 'max:70'],
            'email' => ['required', 'email', 'indisposable', 'max:100', "unique:users,email"],
            'idar'  => ['required', 'integer', 'exists:areas,idar'],
            'estado'=> ['required', 'boolean']
        ]);
    }

    /**
     * Vista para mostrar un solo recurso.
     */
    public function show($idu)
    {
        if ($idu){
            $usuario = User::query()
                           ->where('idu', $idu)
                           ->first();
            if ($usuario){
                return view('users.show', compact('usuario'));
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
    public function edit($idu)
    {
        if ($idu){
            $usuario = User::query()
                           ->where('idu', $idu)
                           ->first();
            if ($usuario) {
                return view('users.edit', compact('usuario'));
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
    public function update(Request $request, $idu)
    {
        if($idu){
            $usuario = User::query()
                           ->where('idu', $idu)
                           ->first();
            if($usuario){
                $request->validate([
                    'idtu'   => ['required', 'integer', 'exists:tipos_usuarios,idtu'],
                    'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5000'],
                    'titulo' => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                                í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                                Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3', 'max:70'],
                    'nombre' => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                                í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                                Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3', 'max:70'],
                    'app'    => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                                í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                                Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3', 'max:70'],
                    'apm'   => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                                í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                                Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3', 'max:70'],
                    'email' => ['required', 'email', 'indisposable', 'max:100', "unique:users,email"],
                    'idar'  => ['required', 'integer', 'exists:areas,idar'],
                    'estado' => ['required', 'boolean']
                ]);

                $actualizar = $usuario->update([
                    'idtu'   => $request->idtu,
                    'titulo' => $request->titulo,
                    'nombre' => $request->nombre,
                    'app'    => $request->app,
                    'apm'    => $request->apm,
                    'email'  => $request->email,
                    'idar'   => $request->idar,
                    'estado' => $request->estado,
                ]);

                return redirect()->route('users.index')->with('mensaje', 'Se ha actualizado correctamente');
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
    public function destroy($idu)
    {
        if ($idu){
            $usuario = User::query()
                           ->where('idu', $idu)
                           ->first();
            if ($usuario){
                $eliminar = $usuario->delete();
                return redirect()->route('users.index')->with('mensaje', 'Se ha eliminado correctamente');
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }
}
