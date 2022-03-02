@extends('layout.layout')
@section('content')

@php
   $mi_id = auth()->user()->idtu_tipos_usuarios;
@endphp

    <div class="card">
            <div class="card-header bg-success text-light" style="text-align: center;">
                <h3>Editar mi perfil</h3>
            </div>
            <div class="card-body">
                <form id="formulario-actualizar-usuario" action="{{ route('editar-perfil.post') }}" method="POST" enctype="multipart/form-data">
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
                            @if(session('mensaje'))
                                <div class="alert alert-info text-justify font-bold p-5">
                                    {{ session('mensaje') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="form-group col-md-6 col-xs-3">
                                    <!-- <label for="idtu_tipos_usuarios">Tipo Usuario:</label> -->
                                        <select class="form-select" id="idtu_tipos_usuarios" name="idtu_tipos_usuarios" {{ ($mi_id == 2 OR $mi_id == 4) ? 'disabled' : '' }} required hidden> 
                                            <option value="">Selección</option>
                                                @foreach($tipos_usuarios as $tipousuario)
                                                    <option value="{{ $tipousuario->idtu }}" {{ (old('idtu_tipos_usuarios', $user_edit->idtu_tipos_usuarios) == $tipousuario->idtu) ? 'selected' : '' }}>{{ $tipousuario->nombre }}</option>
                                                @endforeach
                                        </select>
                                </div>
                                
                            <div class="row">
                                    <div class="form-group col-xs-3 col-md-6">
                                        <label for="titulo">Título:</label>
                                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo', $user_edit->titulo) }}" {{ ($mi_id == 2 OR $mi_id == 4) ? 'disabled' : '' }}>
                                        @error('titulo')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">El título debe ser abreviado y con punto</p>@enderror
                                    </div>
                                    <div class="form-group col-xs-3 col-md-6">
                                        <label for="nombre">Nombre (S):</label>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $user_edit->nombre) }}" {{ ($mi_id == 2 OR $mi_id == 4) ? 'disabled' : '' }}>
                                        @error('nombre')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">El nombre admite solo letras y espacios</p>@enderror
                                    </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-3 col-md-6">
                                    <label for="app">Apellido Paterno:</label>
                                    <input type="text" class="form-control @error('app') is-invalid @enderror" id="app" name="app" value="{{ old('app', $user_edit->app) }}" {{ ($mi_id == 2 OR $mi_id == 4) ? 'disabled' : '' }}>
                                    @error('app')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">El apellido paterno admite solo letras y espacios</p>@enderror
                                </div>
                                <div class="form-group col-xs-3 col-md-6">
                                    <label for="apm">Apellido Materno:</label>
                                    <input type="text" class="form-control @error('apm') is-invalid @enderror" id="apm" name="apm" value="{{ old('apm', $user_edit->apm) }}" {{ ($mi_id == 2 OR $mi_id == 4) ? 'disabled' : '' }}>
                                    @error('apm')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">El apellido materno admite solo letras y espacios</p>@enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-3 col-md-6">
                                    <label for="email">Correo:</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user_edit->email) }}" {{ ($mi_id == 2 OR $mi_id == 4) ? 'disabled' : '' }}>
                                    @error('email')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">Ingresa un correo válido</p>@enderror
                                </div>
                                <div class="form-group col-xs-3 col-md-6">
                                    <label for="idar_areas">Área:</label>
                                    <select class="form-select" id="idar_areas" name="idar_areas" {{ ($mi_id == 2 OR $mi_id == 4) ? 'disabled' : '' }}>
                                        <option value="">Selección</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->idar }}" {{ ($user_edit->idar_areas == $area->idar) ? 'selected' : '' }}>{{ $area->nombretipo . ' - ' . $area->idar_areas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-3 col-md-6">
                                    <label for="password">Contraseña:</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}">
                                    @error('password')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">Ingresa una contraseña válida</p>@enderror
                                </div>
                                <div class="form-group col-xs-3 col-md-6">
                                    <label for="password_confirmation">Confirma la contraseña:</label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}">
                                    @error('password_confirmation')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">Ingresa una contraseña válida</p>@enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-3 col-md-6">
                                    <label for="activo">Activo:</label>
                                    <select class="form-select" id="activo" name="activo" {{ ($mi_id == 2 OR $mi_id == 4) ? 'disabled' : '' }}>
                                        <option value="">Selección</option>
                                        <option value="1" {{ (old('activo', $user_edit->activo) == 1) ? 'selected' : '' }}>Si</option>
                                        <option value="0" {{ (old('activo', $user_edit->activo) == 0) ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-3 pb-3">
                                    <label for="imagen">Imagen:</label>
                                    <br>
                                        <img src="{{ asset("storage/imagenes_perfil/$user_edit->imagen") }}" width="150" height="150">
                                        <input type="file" class="form-control" id="imagen" name="imagen">
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" id="submit" class="btn btn-primary"><i class="fas fa-user-check"></i></button>
                                <a href="{{ url('/panel') }}" class="btn btn-danger"><i class="fas fa-ban"></i></a>
                            </div>
                </form>
        </div>
    </div>
@endsection
