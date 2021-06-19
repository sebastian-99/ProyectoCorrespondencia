@extends('layout.layout')
@section('content')
    @section('header')
        <script src='{{asset('src/js/zinggrid.min.js')}}'></script>
        <script src='{{asset('src/js/zinggrid-es.js')}}'></script>
        <script>
        if (es) ZingGrid.registerLanguage(es, 'custom');
        </script>
    @endsection

{{-- Inicia Reporte --}}
    <div class="card">
        <div class="card-header">
            @if (Session::has('mensaje'))
                <div class="alert alert-success">{{ Session::get('mensaje') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger"><p>¡Ocurrio un error inesperado, revisa nuevamente el formulario!</p></div>
            @endif
            <div class="row">
                <div class="col-sm-11">
                    <h2 align="center">Usuarios</h2>
                      <a href="#crear" class="btn btn-success" data-toggle="modal" data-target="#crearModal">Crear</a>
                </div>
            </div>
        </div>
    </div>

     <div class="card-body">
    	<zing-grid
        	lang="custom"
        	caption='Reporte de tipos de actividades'
        	sort
        	search
        	pager
        	page-size='10'
        	page-size-options='10,15,20,25,30'
        	layout='row'
        	viewport-stop
        	theme='android'
        	id='zing-grid'
        	filter
            data="{{ $json }}">
        	<zg-colgroup>
            	  <zg-column index='idtu_tipos_usuarios' header='Tipo-Uusario'  type='text'></zg-column>
                <zg-column index='imagen' header='Imagen'></zg-column>
                <zg-column index='titulo' header='Título'  type='text'></zg-column>
                <zg-column index='nombre' header='Nombre'  type='text'></zg-column>
                <zg-column index='app' header='Apellido-Paterno'  type='text'></zg-column>
                <zg-column index='apm' header='Apellido-Materno'  type='text'></zg-column>
                <zg-column index='email' header='email'  type='text'></zg-column>
                <zg-column index='idar_areas' header='Área'  type='text'></zg-column>
            	<zg-column index='activo' header='Activo'  type='text'></zg-column>
                <zg-column align="center" filter ="disabled" index='operaciones' header='Operaciones' type='text'></zg-column>
        	</zg-colgroup>
    	</zing-grid>
	</div>
    @foreach ($usuarios as $user)
    <form id="eliminar-usuario-{{ $user->idu }}" class="ocultar" action="{{ route('users.destroy', ['user' => $user->idu]) }}" method="POST">
        @csrf
        @method('DELETE')
    </form>
     @endforeach
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
                <select class="form-select" id="idtu_tipos_usuarios" name="idtu_tipos_usuarios" required>
                  <option value="">Selección</option>
                  @foreach($tipos_usuarios as $tipo_usuario)
                    <option value="{{ $tipo_usuario->idtu }}" {{ (old('idtu_tipos_usuarios') == $tipo_usuario->idtu) ? 'selected' : '' }}>{{ $tipo_usuario->nombre }}</option>
                  @endforeach
                </select>

              </div>
              <div class="">
                <label for="">Imagen:</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
              </div><br>
              <div class="form-group">
                <label for="titulo">Título: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo') }}" placeholder="Ingresa tu titulo" title="Debe ingresar un titulo abreviado 'Ejm.'" required>
                @error('titulo')<p class="form-control-feedback" style="font-size: 14px; font-style: italic;">Debe ser abreviado y con punto</p>@enderror
              </div>
              <div class="form-group">
                <label for="nombre">Nombre (S): <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ingresa tu nombre" title="El nombre admite solo letras y espacios" required>
                @error('nombre')<p class="form-control-feedback" style="font-size: 14px; font-style: italic;">El nombre admite solo letras y espacios</p>@enderror
              </div>
              <div class="form-group">
                <label for="app">Apellido Paterno: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('app') is-invalid @enderror" id="app" name="app" value="{{ old('app') }}" placeholder="Ingresa tu apellido paterno" title="El apellido admite solo letras y espacios" required>
                @error('app')<p class="form-control-feedback" style="font-size: 14px; font-style: italic;">El apellido admite solo letras y espacios</p>@enderror
              </div>
              <div class="form-group">
                <label for="apm">Apellido Materno: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('apm') is-invalid @enderror" id="apm" name="apm" value="{{ old('apm') }}" placeholder="Ingresa tu apellido materno" title="El apellido admite solo letras y espacios" required>
                @error('apm')<p class="form-control-feedback" style="font-size: 14px; font-style: italic;">El apellido admite solo letras y espacios</p>@enderror
              </div>
              <div class="form-group">
                <label for="email">Correo: <b class="text-danger">*</b></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Ingresa un correo válido" required>
                @error('email')<p class="form-control-feedback" style="font-size: 14px; font-style: italic;">El correo admite @, gmail, outlook, hotmail</p>@enderror
              </div>
              <div class="form-group">
                <label for="password">Contraseña: <b class="text-danger">*</b></label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" title="Debe ingresar 8 carácteres, una mayúscula y un número 'Ejem1234'" required>
                @error('password')<p class="form-control-feedback text-danger" style="font-size: 14px; font-style: italic;">La contraseña debe ser minimo 8 carácteres, una mayúscula y un número</p>@enderror
              </div>
              <div class="form-group">
                <label for="idar_areas">Área: <b class="text-danger">*</b></label>
                <select class="form-select @error('idar_areas') is-invalid @enderror" id="idar_areas" name="idar_areas" required>
                    <option value="">Selección</option>
                    @foreach($areas as $area)
                      <option value="{{ $area->idar }}" {{ (old('idar_areas') == $area->idar) ? 'selected' : '' }}>{{ $area->nombre }}</option>
                    @endforeach
                </select>
                @error('idar_areas')<p class="text-danger" style="font-size: 14px; font-style: italic;"></p>@enderror
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
                    <label for="idtu_tipos_usuarios">Tipo Usuario:</label>
                    <select class="form-select" id="idtu_tipos_usuarios" name="idtu_tipos_usuarios">
                    <option value="">Selección</option>
                    @foreach($tipos_usuarios as $tipousuario)
                        <option value="{{ $tipousuario->idtu }}" {{ (old('idtu_tipos_usuarios', $user_edit->idtu_tipos_usuarios) == $tipousuario->nombre) ? 'selected' : '' }}>{{ $tipousuario->nombre }}</option>
                    @endforeach
                    </select>
                </div>
              <div class="">
                <label for="">Imagen:</label>
                <img src="{{ asset("storage/imagenes_perfil/$user_edit->imagen") }}" height="80">
                <input type="file" class="form-control" id="imagen" name="imagen">
              </div><br>
              <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo', $user_edit->titulo) }}">
                @error('titulo')<p class="form-control-feedback" style="font-size: 14px; font-style: italic;">Debe ser abreviado y con punto</p>@enderror
              </div>
              <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $user_edit->nombre) }}">
                @error('nombre')<p class="form-control-feedback" style="font-size: 14px; font-style: italic;">El nombre admite solo letras y espacios</p>@enderror
              </div>
              <div class="form-group">
                <label for="app">Apellido Paterno:</label>
                <input type="text" class="form-control @error('app') is-invalid @enderror" id="app" name="app" value="{{ old('app', $user_edit->app) }}">
                @error('app')<p class="form-control-feedback" style="font-size: 14px; font-style: italic;">El apellido admite solo letras y espacios</p>@enderror
              </div>
              <div class="form-group">
                <label for="apm">Apellido Materno:</label>
                <input type="text" class="form-control @error('apm') is-invalid @enderror" id="apm" name="apm" value="{{ old('apm', $user_edit->apm) }}">
                @error('apm')<p class="form-control-feedback" style="font-size: 14px; font-style: italic;">El apellido admite solo letras y espacios</p>@enderror
              </div>
              <div class="form-group">
                <label for="email">Correo:</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user_edit->email) }}">
                @error('email')<p class="form-control-feedback" style="font-size: 14px; font-style: italic;">El correo admite @, gmail, outlook, hotmail</p>@enderror
              </div>
              <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" title="Para cambiar su contraseña debe ingresar una nueva que contenga 8 carácteres, una mayúscula y un número 'Ejem1234'">
                @error('password')<p class="form-control-feedback text-danger" style="font-size: 14px; font-style: italic;">La contraseña debe ser minimo 8 carácteres, una mayúscula y un número</p>@enderror
              </div>
              <div class="form-group">
                <label for="idar_areas">Área:</label>
                <select class="form-select @error('idar_areas') is-invalid @enderror" id="idar_areas" name="idar_areas">
                    <option value="">Selección</option>
                    @foreach($areas as $area)
                      <option value="{{ $area->idar }}" {{ (old('idar_areas', $user_edit->idar_areas) == $area->nombre) ? 'selected' : '' }}>{{ $area->nombre }}</option>
                    @endforeach
                </select>
                @error('idar_areas')<p class="text-danger" style="font-size: 14px; font-style: italic;"></p>@enderror
              </div>
              <div class="form-group">
                <label for="activo">Activo:</label>
                <select class="form-select @error('activo') is-invalid @enderror" id="activo" name="activo">
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

