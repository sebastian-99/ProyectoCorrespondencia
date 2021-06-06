@extends('layout.layout')
@section('content')

{{-- Inicia Reporte --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-11">
                    <h2 align="center">Usuarios</h2>
                      <a href="#crear" class="btn btn-success" data-toggle="modal" data-target="#crearModal">
                        Crear
                      </a>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="tabla">
            <thead class="text-center">
             <tr style="background-color: #1F75FE; color: #ffffff">

                <th scope="col">#</th>
                <th scope="col">Tipo de usuario</th>
                <th scope="col">Imagen</th>
                <th scope="col">Título</th>
                <th scope="col">Nombre(s)</th>
                <th scope="col">Apellido Paterno</th>
                <th scope="col">Apellido Materno</th>
                <th scope="col">Correo</th>
                <th scope="col">Área</th>
                <th scope="col">Activo</th>
                <th scope="col">Operaciones</th>
            </tr>
        </thead>
        <tbody>
  	        @foreach($usuarios as $user)
                <tr>
                    <td>{{$user->idu}}</td>
                    <td>{{$user->idtu_tipos_usuarios}}</td>
                    <td><img src="{{ asset("storage/imagenes_perfil/$user->imagen") }}" height="80" alt="{{ $user->imagen }}"></td>
                    <td>{{$user->titulo}}</td>
                    <td>{{$user->nombre}}</td>
                    <td>{{$user->app}}</td>
                    <td>{{$user->apm}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->idar_areas}}</td>
                    <td>{{($user->activo == 1) ? 'Si' : 'No' }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="#editar" class="btn btn-primary" data-toggle="modal" data-target="#editarModal-{{ $user->idu }}">
                                Editar
                            </a>
                            <form id="eliminar-usuario-{{ $user->idu }}" class="ocultar" action="{{ route('users.destroy', ['user' => $user->idu]) }}" method="POST">
                              @csrf
                              @method('DELETE')
                            </form>
                            <a href="#eliminar" class="btn btn-danger" onclick="formSubmit('eliminar-usuario-{{ $user->idu }}')">Eliminar</a>
                        </div>
                    </td>
                </tr>
             @endforeach
        </tbody>
    </table>
{{-- Termina Reporte --}}

{{-- Inicia Modal para crear --}}
    <div class="modal fade" id="crearModal" tabindex="-1" role="dialog" aria-labelledby="crearModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="crearModalLabel"><i class="far fa-user-edit"></i> Crear Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('POST')
                <div class="form-group">
                <label for="idtu_tipos_usuarios">Tipo Usuario: <b class="text-danger">*</b></label>
                <select class="form-control @error('idtu_tipos_usuarios') is-invalid @enderror" id="idtu_tipos_usuarios" name="idtu_tipos_usuarios" required>
                  <option value="">Selección</option>
                  @foreach($tipos_usuarios as $tipo_usuario)
                    <option value="{{ $tipo_usuario->idtu }}" {{ (old('idtu_tipos_usuarios') == $tipo_usuario->idtu) ? 'selected' : '' }}>{{ $tipo_usuario->nombre }}</option>
                  @endforeach
                </select>
                @error('idtu_tipos_usuarios')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="">
                <label for="">Imagen:</label>
                <input type="file" class="form-control @error('imagen') is-invalid @enderror" id="imagen" name="imagen">
                @error('imagen')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div><br>
              <div class="form-group">
                <label for="titulo">Título: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                @error('titulo')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="nombre">Nombre: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="app">Apellido Paterno: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('app') is-invalid @enderror" id="app" name="app" value="{{ old('app') }}" required>
                @error('app')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="apm">Apellido Materno: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('apm') is-invalid @enderror" id="apm" name="apm" value="{{ old('apm') }}" required>
                @error('apm')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="email">Correo: <b class="text-danger">*</b></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="idar_areas">Área: <b class="text-danger">*</b></label>
                <select class="form-control @error('idar_areas') is-invalid @enderror" id="idar_areas" name="idar_areas" required>
                    <option value="">Selección</option>
                    @foreach($areas as $area)
                      <option value="{{ $area->idar }}" {{ (old('idar_areas') == $area->idar) ? 'selected' : '' }}>{{ $area->nombre }}</option>
                    @endforeach
                </select>
                @error('idar_areas')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group text-center">
                <button type="submit" class="btn btn-success">Crear</button>
                <button type="reset" class="btn btn-outline-secondary" >Limpiar</button>
              </div>
            </form>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
{{-- Termina Modal de crear --}}

@foreach($usuarios as $user_edit)
{{-- Inicia Modal para editar --}}
    <div class="modal fade" id="editarModal-{{ $user_edit->idu }}" tabindex="-1" role="dialog" aria-labelledby="editarModal-{{ $user_edit->idu }}Label" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarModal-{{ $user_edit->idu }}Label"> Editar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">

            <form action="{{ route('users.update', ['user' => $user_edit->idu]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PATCH')
                <div class="form-group">
                <label for="idtu_tipos_usuarios">Tipo Usuario: <b class="text-danger">*</b></label>
                <select class="form-control @error('idtu_tipos_usuarios') is-invalid @enderror" id="idtu_tipos_usuarios" name="idtu_tipos_usuarios" required>
                  <option value="">Selección</option>
                  @foreach($tipos_usuarios as $tipo_usuario_edit)
                    <option value="{{ $tipo_usuario_edit->idtu }}" {{ (old('idtu_tipos_usuarios', $user_edit->idtu_tipos_usuarios) == $tipo_usuario_edit->idtu) ? 'selected' : '' }}>{{ $tipo_usuario_edit->nombre }}</option>
                  @endforeach
                </select>
                @error('idtu_tipos_usuarios')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="">
                <label for="">Imagen:</label>
                <input type="file" class="form-control @error('imagen') is-invalid @enderror" id="imagen" name="imagen">
                @error('imagen')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div><br>
              <div class="form-group">
                <label for="titulo">Título: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo', $user_edit->titulo) }}" required>
                @error('titulo')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="nombre">Nombre: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $user_edit->nombre) }}" required>
                @error('nombre')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="app">Apellido Paterno: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('app') is-invalid @enderror" id="app" name="app" value="{{ old('app', $user_edit->app) }}" required>
                @error('app')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="apm">Apellido Materno: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('apm') is-invalid @enderror" id="apm" name="apm" value="{{ old('apm', $user_edit->apm) }}" required>
                @error('apm')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="email">Correo: <b class="text-danger">*</b></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user_edit->email) }}" required>
                @error('email')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="idar_areas">Área: <b class="text-danger">*</b></label>
                <select class="form-control @error('idar_areas') is-invalid @enderror" id="idar_areas" name="idar_areas" required>
                    <option value="">Selección</option>
                    @foreach($areas as $area)
                      <option value="{{ $area->idar }}" {{ (old('idar_areas', $user_edit->idar_areas) == $area->idar) ? 'selected' : '' }}>{{ $area->nombre }}</option>
                    @endforeach
                </select>
                @error('idar_areas')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="activo">Activo: <b class="text-danger">*</b></label>
                <select class="form-control @error('activo') is-invalid @enderror" id="activo" name="activo" required>
                  <option value="">Selección</option>
                  <option value="1" {{ (old('activo', $user_edit->activo) == 1) ? 'selected' : '' }}>Si</option>
                  <option value="0" {{ (old('activo', $user_edit->activo) == 0) ? 'selected' : '' }}>No</option>
                </select>
                @error('activo')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <button type="reset" class="btn btn-outline-secondary" >Limpiar</button>
              </div>
            </form>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
{{-- Termina Modal de editar --}}
 @endforeach
@endsection
