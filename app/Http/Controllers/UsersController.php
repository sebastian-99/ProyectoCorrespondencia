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
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    /**
     * Vista para mostrar un listado de recuersos.
     */
    public function index()
    {
        $usuarios = User::query()
                         ->join('tipos_usuarios', 'tipos_usuarios.idtu', '=', 'users.idtu_tipos_usuarios')
                         ->join('areas', 'areas.idar', '=', 'users.idar_areas')
                         ->select(
                                'users.idu',
                                'users.imagen',
                                'users.titulo',
                                'users.nombre',
                                'users.app',
                                'users.apm',
                                'users.email',
                                'users.password',
                                'users.activo',
                                'tipos_usuarios.nombre as idtu_tipos_usuarios',
                                'areas.nombre as idar_areas',
                                )
                         ->get();
        $areas = Areas::all();
        $tipos_usuarios = TiposUsuarios::all();

        $array = array();

        function btn($idu, $activo){
            if($activo == 1){
                $botones = "<a href=\"#eliminar\" class=\"btn btn-danger mt-1\" onclick=\"formSubmit('eliminar-usuario-$idu')\">Desactivar</a>"
                         . "<a href=\"#editar\" class=\"btn btn-primary mt-1\" data-toggle=\"modal\" data-target=\"#editarModal-$idu\">Editar</a>";
            } else {
                $botones = "<a href=\"#activar\" class=\"btn btn-info mt-1\" onclick=\"formSubmit('eliminar-usuario-$idu')\">Activar</a>";
            }
            return $botones;
        }

        foreach ($usuarios as $user){

            array_push($array, array(
                'idu'                 => $user->idu,
                'idtu_tipos_usuarios' => $user->idtu_tipos_usuarios,
                'imagen'              => '<img src="'.asset("storage/imagenes_perfil/$user->imagen").'" height="80">',
                'titulo'              => $user->titulo,
                'nombre'              => $user->nombre,
                'app'                 => $user->app,
                'apm'                 => $user->apm,
                'email'               => $user->email,
                'idar_areas'          => $user->idar_areas,
                'activo'              => ($user->activo == 1) ? "Si" : "No",
                'operaciones'         => btn($user->idu, $user->activo)
            ));
        }

        $json = json_encode($array);

        return view('users', compact('json', 'usuarios', 'areas', 'tipos_usuarios'));
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
            'titulo' => ['required', 'string', "regex:/^[a-z,A-Z, ,.]*$/"],
            'nombre' => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                        í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                        Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                        Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/"],
            'app'    => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                        í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                        Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                        Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/"],
            'apm'   => ['required', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                        í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                        Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                        Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/"],
            'email' => ['required', 'email', 'max:100', "unique:users,email"],
            'password' => ['required',
                       Password::min(8)
                       ->letters()  //Al menos una letra
                       ->mixedCase() //Al menos una minúscula y una mayúscula
                       ->numbers() //Al menos un número
                        ],
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
            'password' => Hash::make($request->password),
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
                    'idtu_tipos_usuarios'   => ['nullable', 'integer', 'exists:tipos_usuarios,idtu'],
                    'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
                    'titulo' => ['nullable', 'string', "regex:/^[a-z,A-Z, ,.]*$/"],
                    'nombre' => ['nullable', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                                í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                                Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/"],
                    'app'    => ['nullable', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                                í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                                Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3'],
                    'apm'   => ['nullable', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                                í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                                Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                                Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ]*$/", 'min:3'],
                    'email' => ['nullable', 'email', 'max:100', "unique:users,email,$idu,idu"],
                    'password' => ['nullable',
                        Password::min(8)
                            ->letters()  //Al menos una letra
                            ->mixedCase() //Al menos una minúscula y una mayúscula
                            ->numbers() //Al menos un número
                        ],
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
                    'password' => Hash::make($request->password),
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
                $eliminar = $usuario->update([
                    'activo' => ($usuario->activo == 1) ? 0 : 1
                ]);
                return redirect()->route('users.index')->with('mensaje', 'Su estado ha cambiado');
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }
}
