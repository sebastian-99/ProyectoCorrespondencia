@extends('layout.layout')
@section('content')

<div class="card">
        <div class="card-header bg-success text-light" style="text-align: center;">
            <h3>Á R E A S</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-center align-items-center">
                <div class="col-sm-6">
                    @foreach($areas as $area_edit)
                        <form id="edit-area-{{ $area_edit->idar }}" action="{{ route('areas.update', ['area' => $area_edit->idar]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $area_edit->nombre) }}" title="El nombre admite solo letras y espacios">
                                        @error('nombre')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">El nombre admite solo letras y espacios</p>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="idtar">Tipo de Área:</label>
                                            <select class="form-select" id="idtar" name="idtar">
                                                <option value="">Selección</option>
                                                @foreach($tipos_areas as $tipoarea)
                                                    <option value="{{ $tipoarea->idtar }}" {{ (old('idtar', $area_edit->idtar) == $tipoarea->idtar) ? 'selected' : '' }}>{{ $tipoarea->nombre }}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="activo">Activo:</label>
                                            <select class="form-select" id="activo" name="activo">
                                                <option value="">Selección</option>
                                                <option value="1" {{ (old('activo', $area_edit->activo) == 1) ? 'selected' : '' }}>Si</option>
                                                <option value="0" {{ (old('activo', $area_edit->activo) == 0) ? 'selected' : '' }}>No</option>
                                            </select>
                                    </div>
                                        <div class="form-group text-center">
                                            <button type="submit" id="submit" class="btn btn-primary"><i class="fas fa-pen-square"></i></button>
                                            <a href="{{ route('areas.index') }}" class="btn btn-danger"><i class="fas fa-ban"></i></a>
                                        </div>
                        </form>
                    @endforeach
                </div>

            </div>
        </div>
</div>
@endsection
