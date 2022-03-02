@extends('layout.layout')
@section('content')

<div class="card">
    <div class="card-header bg-success text-light text-center">
        <h3>U S U A R I O S</h3>
    </div>
    <div class="card-body">
        <form id="formulario-crear-usuario" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="form-group col-md-6 col-xs-3">
                    <label for="idtu_tipos_usuarios">Tipo Usuario: <b class="text-danger">*</b></label>
                    <select class="form-select" id="idtu_tipos_usuarios" name="idtu_tipos_usuarios" required>
                        <option value="">Selección</option>
                        @foreach($tipos_usuarios as $tipousuario)
                            <option value="{{ $tipousuario->idtu }}" {{ (old('idtu_tipos_usuarios') == $tipousuario->idtu) ? 'selected' : '' }}>{{ $tipousuario->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 col-xs-3 pb-3">
                    <label for="imagen">Imagen:</label>
                    <input type="file" class="form-control" id="imagen" name="imagen">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-xs-3 col-md-6">
                    <label for="titulo">Título: <b class="text-danger">*</b></label>
                    <input type="text" class="form-control  @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo') }}" placeholder="Ingresa tu titulo" required>
                    @error('titulo')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">El título debe ser abreviado y con punto</p>@enderror
                </div>

                <div class="form-group col-xs-3 col-md-6">
                    <label for="nombre">Nombre (S): <b class="text-danger">*</b></label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ingresa tu nombre" required>
                    @error('nombre')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">El nombre admite solo letras y espacios</p>@enderror
                </div>
            </div>

            <div class="row">
                <div class="form-group col-xs-3 col-md-6">
                    <label for="app">Apellido Paterno: <b class="text-danger">*</b></label>
                    <input type="text" class="form-control @error('app') is-invalid @enderror" id="app" name="app" value="{{ old('app') }}" placeholder="Ingresa tu apellido paterno" required>
                    @error('app')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">El apellido paterno admite solo letras y espacios</p>@enderror
                </div>

                <div class="form-group col-xs-3 col-md-6">
                    <label for="apm">Apellido Materno: <b class="text-danger">*</b></label>
                    <input type="text" class="form-control @error('apm') is-invalid @enderror" id="apm" name="apm" value="{{ old('apm') }}" placeholder="Ingresa tu apellido materno" required>
                    @error('apm')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">El apellido materno admite solo letras y espacios</p>@enderror
                </div>
            </div>

            <div class="row">
                <div class="form-group col-xs-3 col-md-6">
                    <label for="email">Correo: <b class="text-danger">*</b></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Ingresa un correo válido" required>
                    @error('email')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">Ingresa un correo válido</p>@enderror
                </div>

                <div class="form-group col-xs-3 col-md-6">
                    <label for="password">Contraseña: <b class="text-danger">*</b></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" placeholder="Ingresa una contraseña" required>
                    @error('password')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">Ingresa una contraseña válida</p>@enderror
                </div>
            </div>

            <div class="row">
                <div class="form-group col-xs-3 col-md-6">
                    <label for="idar_areas">Área: <b class="text-danger">*</b></label>
                    <select class="form-select" id="idar_areas" name="idar_areas" required>
                        <option value="">Selección</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->idar }}" {{ (old('idar_areas') == $area->idar) ? 'selected' : '' }}>{{ $area->nombretipo . ' - ' . $area->idar_areas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group text-center">
                <button type="submit" id="submit" class="btn btn-success"><i class="fas fa-user-check"></i></button>
                <button type="reset" class="btn btn-outline-secondary"><i class="fas fa-sync-alt"></i></button>
                <a href="{{ route('users.index') }}" class="btn btn-danger"><i class="fas fa-ban"></i></a>
            </div>
        </form>
    </div>
</div>
@endsection
