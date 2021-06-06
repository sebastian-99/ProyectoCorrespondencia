@extends('layout.layout')
@section('content')

    {{--Inicia Reporte --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-11">
                    <h2 align="center">Áreas</h2>
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
                <th scope="col">Nombre(s)</th>
                <th scope="col">Tipo de areas</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>
  	        @foreach($areas as $area)
                <tr class="text-center">
                    <td>{{$area->idar}}</td>
                    <td>{{$area->nombre}}</td>
                    <td>{{$area->idtar}}</td>
                    <td>
                        <div class="btn-group">
                            <a href="#editar" class="btn btn-primary" data-toggle="modal" data-target="#editarModal-{{ $area->idar }}">
                                Editar
                            </a>
                            <form id="eliminar-area-{{ $area->idar }}" class="ocultar" action="{{ route('areas.destroy', ['area' => $area->idar]) }}" method="POST">
                              @csrf
                              @method('DELETE')
                            </form>
                            <a href="#eliminar" class="btn btn-danger" onclick="formSubmit('eliminar-area-{{ $area->idar }}')">Eliminar</a>
                        </div>
                    </td>
                </tr>

             @endforeach
        </tbody>
    </table>
    {{-- Termina reporte --}}

{{-- Inicia Modal para crear --}}
    <div class="modal fade" id="crearModal" tabindex="-1" role="dialog" aria-labelledby="crearModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="crearModalLabel"><i class="far fa-user-edit"></i> Crear Área</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">

            <form action="{{ route('areas.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('POST')
              <div class="form-group">
                <label for="nombre">Nombre: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="idtar">Tipo de Área: <b class="text-danger">*</b></label>
                <select class="form-control @error('idtar') is-invalid @enderror" id="idtar" name="idtar" required>
                    <option value="">Selección</option>
                    @foreach($tipos_areas as $tipoarea)
                      <option value="{{ $tipoarea->idtar }}" {{ (old('idtar') == $tipoarea->idtar) ? 'selected' : '' }}>{{ $tipoarea->nombre }}</option>
                    @endforeach
                </select>
                @error('idtar')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
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


 @foreach($areas as $area_edit)
{{-- Inicia Modal para editar --}}
    <div class="modal fade" id="editarModal-{{ $area_edit->idar }}" tabindex="-1" role="dialog" aria-labelledby="editarModal-{{ $area_edit->idar }}Label" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarModal-{{ $area_edit->idar }}Label"> Editar Área</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
          </div>
          <div class="modal-body">

            <form action="{{ route('areas.update', ['area' => $area_edit->idar]) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PATCH')
              <div class="form-group">
                <label for="nombre">Nombre: <b class="text-danger">*</b></label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $area_edit->nombre) }}" required>
                @error('nombre')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
              </div>
              <div class="form-group">
                <label for="idtar">Tipo de Área: <b class="text-danger">*</b></label>
                <select class="form-control @error('idtar') is-invalid @enderror" id="idtar" name="idtar" required>
                    <option value="">Selección</option>
                    @foreach($tipos_areas as $tipoarea)
                      <option value="{{ $tipoarea->idtar }}" {{ (old('idtar', $area_edit->idtar) == $tipoarea->idtar) ? 'selected' : '' }}>{{ $tipoarea->nombre }}</option>
                    @endforeach
                </select>
                @error('idtar')<p class="text-danger" style="font-size: 14px; font-style: italic;">{{ $message }}</p>@enderror
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
