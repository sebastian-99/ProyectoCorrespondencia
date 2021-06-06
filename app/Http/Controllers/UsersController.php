<?php

namespace App\Http\Controllers;

use File;
use App\Models\User;
use App\Models\Areas;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TiposUsuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Vista para mostrar un listado de recuersos.
     */
    public function index()
    {
        $usuarios = User::all();
        $areas = Areas::all();
        $tipos_usuarios = TiposUsuarios::all();
        return view('users', compact('usuarios', 'areas', 'tipos_usuarios'));
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
            'idtu_tipos_usuarios'   => ['required', 'integer', 'exists:tipos_usuarios,idtu'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
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
                        Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3'],
            'apm'   => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                        í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                        Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                        Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3'],
            'email' => ['required', 'email', 'max:100', "unique:users,email"],
            'idar_areas'  => ['required', 'integer', 'exists:areas,idar']
        ]);

        if($request->hasFile('imagen')){
            $imagen = $request->file('imagen');
            $nombre_imagen = rand().'_'.$imagen->getClientOriginalName();
            Storage::disk('imagenes_perfil')->put($nombre_imagen, File::get($imagen));
        } else {
            $nombre_imagen = 'default.jpg';
        }

        $guardar = User::create([
            'idtu_tipos_usuarios' => $request->idtu_tipos_usuarios,
            'imagen'   => $nombre_imagen,
            'titulo'   => $request->titulo,
            'nombre'   => $request->nombre,
            'app'      => $request->app,
            'apm'      => $request->apm,
            'email'    => $request->email,
            'password' => Hash::make(Str::random(8)),
            'idar_areas' => $request->idar_areas
        ]);

        return redirect()->route('users.index')->with('mensaje', 'Se ha guardado correctamente');
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
                return view('users', compact('usuario'));
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
                    'idtu_tipos_usuarios'   => ['required', 'integer', 'exists:tipos_usuarios,idtu'],
                    'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
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
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3'],
                    'apm'   => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                                í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                                Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3'],
                    'email' => ['required', 'email', 'max:100', "unique:users,email,$idu,idu"],
                    'idar_areas'  => ['required', 'integer', 'exists:areas,idar'],
                    'activo' => ['required', 'boolean']
                ]);

                if($request->hasFile('imagen')){
                    $imagen = $request->file('imagen');
                    $nombre_imagen = rand().'_'.$imagen->getClientOriginalName();
                    Storage::disk('imagenes_perfil')->put($nombre_imagen, File::get($imagen));

                    $usuario->update([
                        'imagen' => $nombre_imagen
                    ]);
                }

                $actualizar = $usuario->update([
                    'idtu_tipos_usuarios' => $request->idtu_tipos_usuarios,
                    'titulo'   => $request->titulo,
                    'nombre'   => $request->nombre,
                    'app'      => $request->app,
                    'apm'      => $request->apm,
                    'email'    => $request->email,
                    'idar_areas' => $request->idar_areas,
                    'activo'   => $request->activo
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
