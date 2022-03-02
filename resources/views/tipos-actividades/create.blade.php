@extends('layout.layout')
@section('content')

<div class="card">
        <div class="card-header bg-success text-light" style="text-align: center;">
            <h3>TIPOS DE ACTIVIDADES</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-center align-items-center">
                <div class="col-sm-6">
                    <form action="{{ route('tipos-actividades.store') }}" method="POST" enctype="multipart/form-data">
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
                                    <div class="form-group">
                                        <label for="nombre">Nombre: <b class="text-danger">*</b></label>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ingresa el nombre del tipo de actividad" required>
                                        @error('nombre')<p class="form-control text-danger" style="font-size: 14px; font-style: italic;">El nombre admite solo letras y espacios</p>@enderror
                                    </div>
                            <div class="form-group text-center">
                                <button type="submit" id="submit" class="btn btn-success"><i class="fas fa-check-square"></i></button>
                                <button type="reset" class="btn btn-outline-secondary" ><i class="fas fa-sync-alt"></i></button>
                                <a href="{{ route('tipos-actividades.index') }}" class="btn btn-danger"><i class="fas fa-ban"></i></a>
                            </div>
                    </form>
                </div>

            </div>
        </div>
</div>
@endsection
