<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Areas;
use Illuminate\Http\Request;
use App\Models\TiposUsuarios;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CuentasController extends Controller
{
    public function editar_perfil()
    {
        $areas = Areas::join('tipos_areas', 'tipos_areas.idtar', '=', 'areas.idtar')
                        ->select(
                            'areas.idar',
                            'areas.nombre as idar_areas',
                            'tipos_areas.nombre as nombretipo',
                        )
                        ->orderby('nombretipo', 'ASC')
                        ->orderby('idar_areas', 'ASC')
                        ->get();

        $tipos_usuarios = TiposUsuarios::all();

        $user_edit = User::query()
                         ->where('idu', Auth::id())
                         ->first();

        return view('cuentas.edit', compact('areas', 'tipos_usuarios', 'user_edit'));
    }

    public function editar_perfil_post(Request $request)
    {
        $request->validate([
            'idtu_tipos_usuarios'   => ['nullable', 'integer', 'exists:tipos_usuarios,idtu'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
            'titulo' => ['nullable', 'string', "regex:/^[a-z,A-Z, ,.]*$/"],
            'nombre' => ['nullable', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                        í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
             Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
             Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ,.]*$/"],
            'app'    => ['nullable', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                        í,î,ï,ł, ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                        Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                        Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ,.]*$/", 'min:3'],
            'apm'   => ['nullable', 'string', "regex:/^[a-z,A-Z,à,á,â,ä,ã,å,ą,č,ć,ę,è,é,ê,ë,ė,į,ì,
                        í,î,ï,ł,ń,ò,ó,ô,ö,õ,ø,ù,ú,û,ü,ų,ū,ÿ,ý,ż,ź,ñ,ç,č,š,ž,À,Á,Â,Ä,Ã,Å,
                        Ą,Ć,Č,Ė,Ę,È,É,Ê,Ë,Ì,Í,Î,Ï,Į,Ł,Ń,Ò,Ó,Ô,Ö,Õ,Ø,Ù,Ú,Û,Ü,Ų,Ū,Ÿ,Ý,Ż,Ź,
                        Ñ,ß,Ç,Œ,Æ,Č,Š,Ž,∂,ð, ,.]*$/", 'min:3'],
            'email' => ['nullable', 'email', 'max:100', "unique:users,email,".Auth::id().",idu"],
            'password' => ['nullable', 'confirmed', 'min:6'],
            'idar_areas'  => ['nullable', 'integer', 'exists:areas,idar'],
            'activo' => ['nullable', 'boolean']
        ]);

        $usuario = User::query()
                       ->where('idu', Auth::id())
                       ->first();

        if ($request->hasFile('imagen')){
            $imagen = $request->file('imagen');
            $nombre_imagen = rand() . '_' . $imagen->getClientOriginalName();
            Storage::disk('imagenes_perfil')->put($nombre_imagen, File::get($imagen));
            $usuario->update(['imagen' => $nombre_imagen]);
        }

        if($request->password != null){
            if($request->password == $request->password_confirmation){
                $usuario->update(['password' => Hash::make($request->password_confirmation)]);
            }
        }

        if($usuario->idtu_tipos_usuarios == 1 OR $usuario->idtu_tipos_usuarios == 3){
            $usuario->update([
                'idtu_tipos_usuarios' => $request->idtu_tipos_usuarios,
                'titulo'   => $request->titulo,
                'nombre'   => $request->nombre,
                'app'      => $request->app,
                'apm'      => $request->apm,
                'email'    => $request->email,
                'idar_areas' => $request->idar_areas,
                'activo'   => $request->activo
            ]);
        }

        return back()->with('mensaje','Actualizado correctamente');
    }
}
